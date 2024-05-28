<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name', 'group_id', 'status'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function goods()
    {
        return $this->hasMany(Good::class);
    }
}
