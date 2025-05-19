<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable= ['user_id', 'shipment_id', 'item_id', 'quantity_scanned', 'status', 'scanned_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    Public function shipment()
    {
        return $this->belongsTo(shipment::class);
    }
    public function item()
    {
        return $this->belongsTo(imte::class);
    }
}
