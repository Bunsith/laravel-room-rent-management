<?php

namespace App\Http\Controllers;

use App\Http\Requests\FacilityRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('facilities.manage');

        $search = $request->string('search')->toString();

        $facilities = Facility::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('facilities.index', [
            'facilities' => $facilities,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('facilities.manage');

        return view('facilities.create', [
            'facility' => new Facility(),
        ]);
    }

    public function store(FacilityRequest $request): RedirectResponse
    {
        Gate::authorize('facilities.manage');

        Facility::create($request->validated());

        return redirect()->route('facilities.index')->with('status', 'Facility created successfully.');
    }

    public function edit(Facility $facility): View
    {
        Gate::authorize('facilities.manage');

        return view('facilities.edit', [
            'facility' => $facility,
        ]);
    }

    public function update(FacilityRequest $request, Facility $facility): RedirectResponse
    {
        Gate::authorize('facilities.manage');

        $facility->update($request->validated());

        return redirect()->route('facilities.index')->with('status', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        Gate::authorize('facilities.manage');

        $facility->delete();

        return redirect()->route('facilities.index')->with('status', 'Facility deleted successfully.');
    }
}
