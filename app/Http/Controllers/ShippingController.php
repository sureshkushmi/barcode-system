<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingSetting;
use App\Models\Shipment;

class ShippingController extends Controller
{
    // Show form to edit ShippingEasy configuration
    public function edit()
    {
        $settings = ShippingSetting::first(); // assuming only one row
        return view('shipping.edit', compact('settings'));
    }

    // Save/update the ShippingEasy config
    public function update(Request $request)
    {
        $request->validate([
            'api_key' => 'required',
            'api_secret' => 'required',
            'store_api_key' => 'required',
            'api_url' => 'required|url',
        ]);

        $settings = ShippingSetting::first();
        if (!$settings) {
            $settings = new ShippingSetting();
        }

        $settings->api_key = $request->api_key;
        $settings->api_secret = $request->api_secret;
        $settings->store_api_key = $request->store_api_key;
        $settings->api_url = $request->api_url;
        $settings->save();

        return redirect()->back()->with('success', 'ShippingEasy settings updated successfully.');
    }

    // List of all shipments
    public function indexShipments()
    {
        $shipments = Shipment::orderBy('created_at', 'desc')->paginate(15);
        return view('shipping.shipments', compact('shipments'));
    }
}
