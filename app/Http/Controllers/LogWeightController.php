<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogWeightController extends Controller
{
    public function index(Request $request)
    {
        return view('log_weight.index');
    }
}
