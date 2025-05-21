<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ShippingSetting extends Model
{
    use HasFactory;
    protected $fillable = ['api_key', 'api_secret', 'store_api_key', 'api_url'];
}
