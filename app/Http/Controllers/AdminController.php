<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function test()
    {
        if (Auth::user()->isAdmin() == false){
            return "user";
        }
        else{
            return "admin";
        }
    }
}
