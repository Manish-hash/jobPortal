<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //This method shows the home page
    public function index(){
        return view('frontend.home');
    }
}
