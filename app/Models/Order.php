<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'shippingeasy_order_id',
        'customer_name',
        'customer_email',
        'status',
        'order_date',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];
    // Order.php
public function items()
{
    return $this->hasMany(Item::class);
}

    public function scans()
{
    return $this->hasMany(Scan::class, 'shippingeasy_order_id', 'shippingeasy_order_id');
}

}
