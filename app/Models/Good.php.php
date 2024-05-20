<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock_quantity', 'status', 'group_id', 'attribute_id'];

    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault();
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class)->withDefault();
    }
}
