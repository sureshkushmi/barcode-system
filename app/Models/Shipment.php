<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = ['tracking_number', 'status'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
