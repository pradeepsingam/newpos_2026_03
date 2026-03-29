<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $business = Business::query()->findOrFail((int) auth()->user()->business_id);

        return view('settings.edit', [
            'business' => $business,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $business = Business::query()->findOrFail((int) auth()->user()->business_id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'points_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $business->name = $data['name'];
        $business->points_percentage = $data['points_percentage'];

        if ($request->hasFile('logo')) {
            $directory = public_path('uploads/logos');
            File::ensureDirectoryExists($directory);

            $extension = $request->file('logo')->getClientOriginalExtension();
            $filename = 'business-' . $business->id . '-' . time() . '.' . $extension;
            $request->file('logo')->move($directory, $filename);

            if ($business->logo_path) {
                $oldLogo = public_path($business->logo_path);
                if (File::exists($oldLogo)) {
                    File::delete($oldLogo);
                }
            }

            $business->logo_path = 'uploads/logos/' . $filename;
        }

        $business->save();

        return redirect()
            ->route('settings.edit')
            ->with('status', 'Store settings updated successfully.');
    }
}
