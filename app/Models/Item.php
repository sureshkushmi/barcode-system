<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'name',
        'barcode',
        'required_quantity',
        'scanned_quantity',
        'completed',
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
}
