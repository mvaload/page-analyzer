<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function show(Request $request)
    {
        $urlErrorMessage = $request->input('urlErrorMessage');
        return view('index', ['urlErrorMessage' => $urlErrorMessage]);
    }
}