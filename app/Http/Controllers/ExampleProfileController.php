<?php

namespace App\Http\Controllers;

class ExampleProfileController extends Controller
{
    public function child()
    {
        return view('profile_pages.example.child');
    }

    public function teenager()
    {
        return view('profile_pages.example.teenager');
    }

    public function adults()
    {
        return view('profile_pages.example.adults');
    }
}
