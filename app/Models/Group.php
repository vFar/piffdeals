<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'status', 'category_id'];

    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Good::class);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    public function goods()
    {
        return $this->hasMany(Good::class);
    }
}
