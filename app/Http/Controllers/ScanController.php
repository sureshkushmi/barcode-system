<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Item;

class ScanController extends Controller
{
    public function scanLabelForm()
    {
        return view('scan.label');
    }

    public function handleLabel(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $shipment = Shipment::where('tracking_number', $request->tracking_number)->first();

        if (!$shipment) {
            return redirect()->back()->withErrors(['tracking_number' => 'Shipment not found']);
        }

        return redirect()->route('scan.items', $shipment->id);
    }

    public function scanItems($shipmentId)
    {
        $shipment = Shipment::with('items')->findOrFail($shipmentId);
        return view('scan.items', compact('shipment'));
    }

    public function updateItem(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        $item->scanned_quantity++;
        if ($item->scanned_quantity >= $item->required_quantity) {
            $item->completed = true;
        }
        $item->save();

        return back()->with('success', 'Item scanned');
    }

    public function nextLabel()
    {
        return redirect()->route('scan.label.form')->with('success', 'Ready for next shipment');
    }
}
