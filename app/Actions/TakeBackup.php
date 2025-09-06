<?php

namespace App\Actions;

use App\Models\Backup;
use App\Models\System;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class TakeBackup
{
    public function handle(System $system): Backup
    {
        $timestamp = now()->format('Y_m_d_His');
        $fileName = "{$system->slug}_{$timestamp}";
        $fileNameExt = "{$fileName}.sql.gz";
        $directory = "backups/{$system->id}";
        $storagePath = "{$directory}/{$fileNameExt}";
        $fullPath = storage_path("app/{$storagePath}");

        if (!is_dir(storage_path("app/{$directory}"))) {
            mkdir(storage_path("app/{$directory}"), 0755, true);
        }

        $process = new Process([
            'mysqldump',
            '-h',
            $system->db_host,
            '-P',
            $system->db_port,
            '-u',
            $system->db_username,
            "-p{$system->db_password}",
            $system->db_name
        ]);

        $process->setTimeout(3600);
        $process->run(function ($type, $buffer) use ($fullPath) {
            if ($type === Process::OUT) {
                file_put_contents($fullPath, gzencode($buffer), FILE_APPEND);
            }
        });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException("Backup failed: " . $process->getErrorOutput());
        }

        return Backup::create([
            'system_id' => $system->id,
            'file_name' => $fileName,
            'file_path' => $storagePath,
            'storage_type' => 'local',
            'file_size' => filesize($fullPath),
        ]);
    }
}
