<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category');
    }
}