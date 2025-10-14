<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        // Imagine pulling latest adverts, categories, featured ads, and blog posts
        return view('public.home');
    }
}
