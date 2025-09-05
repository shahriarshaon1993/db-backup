<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSystemRequest;
use Illuminate\Http\Request;

class SystemController
{
    public function store(StoreSystemRequest $request)
    {
        $attributes = $request->validated();

        dd($attributes);
    }
}
