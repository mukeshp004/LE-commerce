<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeFamily extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['code', 'name', 'is_user_defined'];
}
