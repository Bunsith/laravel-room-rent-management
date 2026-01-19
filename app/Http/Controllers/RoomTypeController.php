<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RoomTypeController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('room_types.manage');

        $search = $request->string('search')->toString();

        $roomTypes = RoomType::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('room_types.index', [
            'roomTypes' => $roomTypes,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('room_types.manage');

        return view('room_types.create', [
            'roomType' => new RoomType(),
        ]);
    }

    public function store(RoomTypeRequest $request): RedirectResponse
    {
        Gate::authorize('room_types.manage');

        RoomType::create($request->validated());

        return redirect()->route('room-types.index')->with('status', 'Room type created successfully.');
    }

    public function edit(RoomType $roomType): View
    {
        Gate::authorize('room_types.manage');

        return view('room_types.edit', [
            'roomType' => $roomType,
        ]);
    }

    public function update(RoomTypeRequest $request, RoomType $roomType): RedirectResponse
    {
        Gate::authorize('room_types.manage');

        $roomType->update($request->validated());

        return redirect()->route('room-types.index')->with('status', 'Room type updated successfully.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        Gate::authorize('room_types.manage');

        $roomType->delete();

        return redirect()->route('room-types.index')->with('status', 'Room type deleted successfully.');
    }
}
