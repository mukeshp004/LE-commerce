<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'firstn_name', 'Last_name', 'email', 'phone', 'email_verified_at', 'phone_verified_at', 'status'
    ];
}
