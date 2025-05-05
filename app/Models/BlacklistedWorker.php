<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistedWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'reason', 'proof', 'reported_by', 'approved'
    ];
}
