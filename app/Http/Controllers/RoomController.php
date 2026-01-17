<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\Facility;
use App\Models\Floor;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $rooms = Room::with(['floor', 'roomType', 'facilities'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('floor', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('roomType', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('rooms.index', [
            'rooms' => $rooms,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('rooms.create', [
            'floors' => Floor::orderBy('name')->get(),
            'roomTypes' => RoomType::orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
            'room' => new Room(),
        ]);
    }

    public function store(RoomRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('photo')) {
            $payload['photo'] = $request->file('photo')->store('rooms', 'public');
        }

        $room = Room::create($payload);
        $room->facilities()->sync($payload['facilities'] ?? []);

        return redirect()->route('rooms.index')->with('status', 'Room created successfully.');
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', [
            'room' => $room->load('facilities'),
            'floors' => Floor::orderBy('name')->get(),
            'roomTypes' => RoomType::orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
        ]);
    }

    public function update(RoomRequest $request, Room $room): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('photo')) {
            if ($room->photo) {
                Storage::disk('public')->delete($room->photo);
            }
            $payload['photo'] = $request->file('photo')->store('rooms', 'public');
        }

        $room->update($payload);
        $room->facilities()->sync($payload['facilities'] ?? []);

        return redirect()->route('rooms.index')->with('status', 'Room updated successfully.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->photo) {
            Storage::disk('public')->delete($room->photo);
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('status', 'Room deleted successfully.');
    }
}
