<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = ['sku', 'name', 'description', 'price', 'stock_quantity', 'status', 'group_id', 'attribute_id', 'image'];

    public function getImageAttribute($value)
    {
        if (!$value) {
            return url('/images/S-1.png'); // Ensure this path is correct
        }
        return url($value);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class)->withDefault();
    }

    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault();
    }
}

