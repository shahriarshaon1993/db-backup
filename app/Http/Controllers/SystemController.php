<?php

namespace App\Http\Controllers;

use App\Actions\CreateSystem;
use App\Actions\TakeBackup;
use App\Http\Requests\StoreSystemRequest;
use App\Models\System;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SystemController
{
    public function store(StoreSystemRequest $request, CreateSystem $action): RedirectResponse
    {
        $action->handle($request->validated());

        return back()->with('success', 'New system created successfully!');
    }

    public function show(System $system): Response
    {
        return Inertia::render('systems/Create', [
            'system' => $system,
        ]);
    }

    public function backup(System $system, TakeBackup $action): RedirectResponse
    {
        $backup = $action->handle($system);

        return back()->with([
            'success' => 'Backup created successfully',
            'backup' => $backup
        ]);
    }
}
