<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    // Define a one-to-many relationship
    public function items()
    {
        return $this->hasMany(CartItems::class, 'carts_id'); // make sure 'carts_id' matches the foreign key in your migration/schema
    }
}
