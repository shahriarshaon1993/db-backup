<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteBackupRequest;
use App\Models\Backup;
use Illuminate\Http\Request;

class BackupController
{
    public function download(Backup $backup)
    {
        $path = storage_path("app/{$backup->file_path}");

        if (!file_exists($path)) {
            abort(404, "Backup file not found");
        }

        $fileName = pathinfo($backup->file_name, PATHINFO_EXTENSION)
            ? $backup->file_name
            : basename($path);

        return response()->download($path, $fileName, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

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
