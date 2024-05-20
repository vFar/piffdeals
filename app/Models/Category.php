<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'status'];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
