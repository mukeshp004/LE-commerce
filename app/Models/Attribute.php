<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    public $translatedAttributes = ['name', 'slug'];

    protected $fillable = [
        'uuid',
        'name'
    ];
}
