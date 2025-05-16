<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    protected $fillable = ['api_key', 'api_secret', 'store_api_key', 'api_url'];
}
