<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        Gate::authorize('settings.manage');

        return view('settings.index', [
            'setting' => Setting::first() ?? new Setting(),
        ]);
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        Gate::authorize('settings.manage');

        $payload = $request->validated();
        $setting = Setting::first() ?? new Setting();

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $payload['logo'] = $request->file('logo')->store('settings', 'public');
        }

        $setting->fill($payload)->save();

        return back()->with('status', 'Settings updated successfully.');
    }
}
