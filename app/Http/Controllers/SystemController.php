<?php

namespace App\Http\Controllers;

use App\Actions\CreateSystem;
use App\Http\Requests\StoreSystemRequest;

class SystemController
{
    public function store(StoreSystemRequest $request, CreateSystem $action)
    {
        $action->handle($request->validated());

        return back()->with('success', 'New system created successfully!');
    }
}
