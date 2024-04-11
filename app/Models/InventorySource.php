<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySource extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'status'
    ];

}
