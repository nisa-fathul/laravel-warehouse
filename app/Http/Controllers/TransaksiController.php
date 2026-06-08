<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function in()
    {
        return view('pages.delivery_in', [

        ]);
    }

    public function out()
    {
        return view('pages.delivery_out', [

        ]);
    }
}
