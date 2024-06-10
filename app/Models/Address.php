<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'street',
        'city',
        'postal_code',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
