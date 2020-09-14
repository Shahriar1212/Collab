<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InitiateMessage extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function sendMessage(){
        $user_id = Auth()->user()->id;
        echo($user_id);
    }
}
