<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForecastController extends Controller
{
    public function index()
    {
        return view('pages.forecast',[

        ]);
    }
}
