<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteBackupRequest;
use App\Models\Backup;
use Illuminate\Http\Request;

class BackupController
{
    public function destroy(DeleteBackupRequest $request)
    {
        $data = (array) $request->validated('ids');

        Backup::query()
            ->whereIn('id', $data)
            ->delete();

        return back()->with([
            'success' => 'Backup successfully move to bin',
        ]);
    }
}
