<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductBoxController extends Controller
{
    public function index()
    {
        return view('product_box.index');
    }
}
