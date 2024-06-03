<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = ['sku', 'name', 'description', 'price', 'stock_quantity', 'status', 'group_id', 'attribute_id', 'image'];

    public function getImageAttribute($value)
    {
        return $value ? asset("storage/images/goods/$value") : asset('images/S-1.png');
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

