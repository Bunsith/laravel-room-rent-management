<?php

namespace App\Http\Controllers;

use App\Http\Requests\FloorRequest;
use App\Models\Floor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FloorController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('floors.manage');

        $search = $request->string('search')->toString();

        $floors = Floor::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('floors.index', [
            'floors' => $floors,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('floors.manage');

        return view('floors.create', [
            'floor' => new Floor(),
        ]);
    }

    public function store(FloorRequest $request): RedirectResponse
    {
        Gate::authorize('floors.manage');

        Floor::create($request->validated());

        return redirect()->route('floors.index')->with('status', 'Floor created successfully.');
    }

    public function edit(Floor $floor): View
    {
        Gate::authorize('floors.manage');

        return view('floors.edit', [
            'floor' => $floor,
        ]);
    }

    public function update(FloorRequest $request, Floor $floor): RedirectResponse
    {
        Gate::authorize('floors.manage');

        $floor->update($request->validated());

        return redirect()->route('floors.index')->with('status', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor): RedirectResponse
    {
        Gate::authorize('floors.manage');

        $floor->delete();

        return redirect()->route('floors.index')->with('status', 'Floor deleted successfully.');
    }
}
