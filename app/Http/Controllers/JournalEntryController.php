<?php

namespace App\Http\Controllers;

use App\Http\Requests\JournalEntryRequest;
use App\Models\AccountType;
use App\Models\Floor;
use App\Models\JournalEntry;
use App\Models\ResourceBudget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JournalEntryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $entries = JournalEntry::with(['accountType', 'resourceBudget', 'floor'])
            ->when($search, function ($query) use ($search) {
                $query->where('note', 'like', "%{$search}%");
            })
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();

        return view('journal_entries.index', [
            'entries' => $entries,
            'search' => $search,
            'accountTypes' => AccountType::orderBy('name')->get(),
            'resourceBudgets' => ResourceBudget::orderBy('name')->get(),
            'floors' => Floor::orderBy('name')->get(),
            'entry' => new JournalEntry(),
        ]);
    }

    public function store(JournalEntryRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('attachment')) {
            $payload['attachment'] = $request->file('attachment')->store('journal', 'public');
        }

        JournalEntry::create($payload);

        return back()->with('status', 'Journal entry created successfully.');
    }

    public function update(JournalEntryRequest $request, JournalEntry $journalEntry): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('attachment')) {
            if ($journalEntry->attachment) {
                Storage::disk('public')->delete($journalEntry->attachment);
            }
            $payload['attachment'] = $request->file('attachment')->store('journal', 'public');
        }

        $journalEntry->update($payload);

        return back()->with('status', 'Journal entry updated successfully.');
    }

    public function destroy(JournalEntry $journalEntry): RedirectResponse
    {
        $journalEntry->delete();

        return back()->with('status', 'Journal entry deleted successfully.');
    }
}
