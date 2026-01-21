<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('reports.view');

        [$start, $end] = $this->resolveDateRange($request);

        $totalPaid = (float) Payment::query()
            ->whereBetween('paid_at', [$start, $end])
            ->sum('amount');

        $invoiceQuery = Invoice::query()->whereBetween('invoice_date', [$start, $end]);

        $totalInvoiced = (float) $invoiceQuery->sum('total_amount');
        $totalDue = (float) $invoiceQuery->sum('due_amount');

        $dailyRevenue = $this->dailyRevenue($start, $end);
        $monthlyRevenue = $this->monthlyRevenue($start, $end);

        $totalRooms = Room::query()->where('is_active', true)->count();
        $rentedRooms = Room::query()
            ->where('is_active', true)
            ->whereHas('rentals', function ($query) {
                $query->where('status', 'rented');
            })
            ->count();
        $availableRooms = max($totalRooms - $rentedRooms, 0);
        $occupancyRate = $totalRooms > 0 ? round(($rentedRooms / $totalRooms) * 100, 1) : 0;

        $statusBreakdown = $this->statusBreakdown($start, $end);

        return view('reports.index', [
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'totals' => [
                'paid' => $totalPaid,
                'invoiced' => $totalInvoiced,
                'due' => $totalDue,
            ],
            'dailyRevenue' => $dailyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'occupancy' => [
                'total' => $totalRooms,
                'rented' => $rentedRooms,
                'available' => $availableRooms,
                'rate' => $occupancyRate,
            ],
            'statusBreakdown' => $statusBreakdown,
        ]);
    }

    private function resolveDateRange(Request $request): array
    {
        $startInput = $request->query('start');
        $endInput = $request->query('end');
        $range = (int) $request->query('range', 30);

        if ($startInput && $endInput) {
            try {
                $start = Carbon::parse($startInput)->startOfDay();
                $end = Carbon::parse($endInput)->endOfDay();
                if ($start->lte($end)) {
                    return [$start, $end];
                }
            } catch (\Throwable $e) {
                // Fall back to range defaults.
            }
        }

        $range = $range > 0 ? $range : 30;
        $end = now()->endOfDay();
        $start = now()->subDays($range - 1)->startOfDay();

        return [$start, $end];
    }

    private function dailyRevenue(Carbon $start, Carbon $end): array
    {
        $rows = Payment::query()
            ->whereBetween('paid_at', [$start, $end])
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay());
        $daily = [];

        foreach ($period as $date) {
            $key = $date->toDateString();
            $daily[] = [
                'date' => $key,
                'total' => (float) ($rows[$key]->total ?? 0),
            ];
        }

        return $daily;
    }

    private function monthlyRevenue(Carbon $start, Carbon $end): array
    {
        $rows = Payment::query()
            ->whereBetween('paid_at', [$start, $end])
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $period = CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth());
        $monthly = [];

        foreach ($period as $date) {
            $key = $date->format('Y-m');
            $monthly[] = [
                'month' => $key,
                'total' => (float) ($rows[$key]->total ?? 0),
            ];
        }

        return $monthly;
    }

    private function statusBreakdown(Carbon $start, Carbon $end): array
    {
        $rows = Invoice::query()
            ->whereBetween('invoice_date', [$start, $end])
            ->selectRaw('status, COUNT(*) as count, SUM(total_amount) as total, SUM(due_amount) as due')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $statuses = ['paid', 'partial', 'unpaid'];
        $breakdown = [];

        foreach ($statuses as $status) {
            $row = $rows->get($status);
            $breakdown[] = [
                'status' => $status,
                'count' => (int) ($row->count ?? 0),
                'total' => (float) ($row->total ?? 0),
                'due' => (float) ($row->due ?? 0),
            ];
        }

        return $breakdown;
    }
}
