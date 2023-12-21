<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class yourcontroller extends Controller
{
    public function trigger404()
    {
        abort(500);
    }
}
