<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'position', 'is_user_defined'];

    /**
     * Get the attributes that owns the attribute group.
     */
    // public function custom_attributes()
    // {
    //     return $this->belongsToMany(AttributeProxy::modelClass(), 'attribute_group_mappings')
    //         ->withPivot('position')
    //         ->orderBy('pivot_position', 'asc');
    // }
}
