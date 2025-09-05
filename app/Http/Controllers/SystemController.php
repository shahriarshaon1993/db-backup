<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController
{
    public function store(Request $request)
    {
        dd($request->input());
    }
}
