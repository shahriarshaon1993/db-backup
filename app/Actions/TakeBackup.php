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
        $fileNameExt = "{$fileName}.zip";
        $directory = "backups/{$system->id}";
        $storagePath = "{$directory}/{$fileNameExt}";
        $fullPath = storage_path("app/{$storagePath}");

        if (!is_dir(storage_path("app/{$directory}"))) {
            mkdir(storage_path("app/{$directory}"), 0755, true);
        }

        // Temporary SQL dump path
        $tempSqlPath = storage_path("app/{$directory}/{$fileName}.sql");

        // Generate SQL dump
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
        $process->run(function ($type, $buffer) use ($tempSqlPath) {
            if ($type === Process::OUT) {
                file_put_contents($tempSqlPath, $buffer, FILE_APPEND);
            }
        });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException("Backup failed: " . $process->getErrorOutput());
        }

        // Create Zip file
        $zip = new \ZipArchive();
        if ($zip->open($fullPath, \ZipArchive::CREATE) !== true) {
            throw new \RuntimeException("Cannot create zip file at: $fullPath");
        }

        $zip->addFile($tempSqlPath, basename($tempSqlPath));
        $zip->close();

        unlink($tempSqlPath);

        return Backup::create([
            'system_id' => $system->id,
            'file_name' => $fileNameExt, // zip file name
            'file_path' => $storagePath,
            'storage_type' => 'local',
            'file_size' => filesize($fullPath),
        ]);
    }
}
