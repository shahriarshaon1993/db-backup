<?php

namespace App\Http\Controllers;

use App\Actions\CreateSystem;
use App\Actions\TakeBackup;
use App\Http\Requests\StoreSystemRequest;
use App\Http\Resources\SystemResource;
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
        $system = $system->load(['backups', 'createdBy']);

        return Inertia::render('systems/Create', [
            'system' => new SystemResource($system),
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
