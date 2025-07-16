<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
         'barcode',
        'shipment_id',
        'order_id',
        'name',
        'quantity',
        'required_quantity',
        'total_quantity',
        'completed',
        'scanned_quantity',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    // Relationships
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function scans()
    {
        return $this->hasMany(Scan::class);
    }
    public function kitItems()
        {
            return $this->hasMany(KitItem::class);
        }
}
