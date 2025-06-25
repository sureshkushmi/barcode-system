<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
    use HasFactory;
    protected $fillable  = ['shipment_id','barcode','name'];
    public function shipment()
    {
        return $this->belongsTo(shipment::class);
    }
    public function kitItems()
    {
        return $this->belongsTo(KitItem::class);
    }
    public function items()
    {
        return $this->belongsToMany(Item::class,'kit_items')
                    ->withPivot('quantity');
    }
}
