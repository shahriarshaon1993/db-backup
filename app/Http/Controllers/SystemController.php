<?php

namespace App\Http\Controllers;

use App\Actions\CreateSystem;
use App\Actions\GetSystemBackups;
use App\Actions\TakeBackup;
use App\Http\Requests\StoreSystemRequest;
use App\Http\Resources\BackupResource;
use App\Http\Resources\SystemResource;
use App\Models\System;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SystemController
{
    public function store(StoreSystemRequest $request, CreateSystem $action): RedirectResponse
    {
        $action->handle($request->validated());

        return back()->with('success', 'New system created successfully!');
    }

    public function show(Request $request, System $system, GetSystemBackups $action): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        [$system, $backups] = $action->handle($request, $system);

        return Inertia::render('systems/Create', [
            'system' => new SystemResource($system),
            'backups' => BackupResource::collection($backups),
            'filters' => $request->only(['search', 'per_page']),
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
