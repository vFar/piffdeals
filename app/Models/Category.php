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

    public static function getActiveCategories()
    {
        return self::where('status', 'Aktīvs')->get();
    }
}
