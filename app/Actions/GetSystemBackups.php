<?php

namespace App\Actions;

use App\Models\System;
use Illuminate\Http\Request;

class GetSystemBackups
{
    public function handle(Request $request, System $system)
    {
        $system = $system->load('createdBy');

        $backups = $system->backups()
            ->search($request->query('search'))
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return [$system, $backups];
    }
}
