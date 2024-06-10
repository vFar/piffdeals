<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    use HasFactory;

    protected $fillable = ['carts_id', 'good_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'carts_id');
    }

// In CartItem model
public function good()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }

}
