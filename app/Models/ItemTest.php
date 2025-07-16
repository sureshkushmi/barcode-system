<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTest extends Model
{
    protected $table = 'itemtest';

    protected $fillable = [
        'barcode',
        'shipment_id',
        'order_id',
        'name',
        'quantity',
        'required_quantity',
        'total_quantity',
        'completed',
        'scanned_quantity'
    ];
}
