<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class Login extends Controller
{
    public function LoginView()
    {
        return view('login');
    }
}
