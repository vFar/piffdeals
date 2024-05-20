<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'group_id', 'status'];

    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault();
    }

    public function products()
    {
        return $this->hasMany(Good::class);
    }
}
