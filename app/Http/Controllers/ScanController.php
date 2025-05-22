<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Shipment;



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


    public function scanItemForm()
    {
        return view('scan.items');
    }
    public function scanItems($shipmentId)
    {
        $shipment = Shipment::with('items')->findOrFail($shipmentId);
        return view('scan.items', compact('shipment'));
    }

    
    public function updateItem($itemId)
    {
        // Find the item, not shipment
        $item = Item::findOrFail($itemId);
    
        // Defensive check for shipment relation
        $shipment = $item->shipment;
        if (!$shipment) {
            return redirect()->back()->with('error', 'Shipment not found for this item.');
        }
    
        $item->scanned_quantity = ($item->scanned_quantity ?? 0) + 1;
    
        if ($item->scanned_quantity >= $item->required_quantity) {
            $item->completed = true;
        }
    
        $item->save();
    
        $allCompleted = $shipment->items->every(fn($i) => $i->completed);
    
        if ($allCompleted) {
            return redirect()->route('scan.label.form')->with('success', 'Shipment completed! Ready for next.');
        }
    
        return redirect()->back()->with('success', 'Item scanned.');
    }
    

    
    

    public function nextShipment()
    {
        // Find the next shipment that is not completed
    $nextShipment = Shipment::where('status', '!=', 'completed')->orderBy('id')->first();

    if ($nextShipment) {
        return redirect()->route('scan.items', $nextShipment->id);
    } else {
        return redirect()->route('scan.label.form')->with('success', 'All shipments are completed!');
    }
    }
}
