<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'shipment_id', 'name', 'barcode',
        'required_quantity', 'scanned_quantity', 'completed'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
