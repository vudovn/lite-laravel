<?php

namespace App\Controllers;

use Framework\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('home', [
            'title' => 'Welcome to LitePHP',
            'description' => 'A lightweight PHP framework inspired by Laravel'
        ]);
    }

    public function about(Request $request)
    {
        return view('about', [
            'title' => 'About Us',
            'content' => 'This is the about page of our application.'
        ]);
    }

    public function contact(Request $request)
    {
        return view('contact', [
            'title' => 'Contact Us',
            'email' => 'contact@litephp.example'
        ]);
    }
}
