<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipment_id',
        'item_id',
        'quantity_scanned',
        'scanned_at',
        'status',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];
}
