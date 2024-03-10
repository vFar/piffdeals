<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogFile extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'log_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
