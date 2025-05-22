<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;  // Correct import here

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'status',
    ];

    // Correct relationship:
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
