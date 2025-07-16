<?php

// app/Models/KitItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    protected $fillable = ['kit_id', 'item_id', 'quantity'];

    public function kit()
    {
        return $this->belongsTo(Kit::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
