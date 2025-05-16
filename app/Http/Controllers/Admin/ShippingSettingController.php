<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingSetting;

class ShippingSettingController extends Controller
{
    public function edit()
    {
        $settings = ShippingSetting::first();
        return view('admin.shipping.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
            'store_api_key' => 'nullable|string',
            'api_url' => 'nullable|url',
        ]);

        $settings = ShippingSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            ShippingSetting::create($validated);
        }

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
}
