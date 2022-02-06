<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'position', 'is_user_defined', 'attribute_family_id'];


    protected $with = ['attributes'];


    /**
     * Get the attributes that owns the attribute group.
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_group_mappings')
            ->withPivot('position')
            ->orderBy('pivot_position', 'asc');
    }
}
