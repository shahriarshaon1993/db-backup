<?php

namespace App\Jobs;

use App\Models\Backup;
use App\Models\System;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class TakeDatabaseBackupJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public System $system)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $timestamp = now()->format('Y_m_d_His');
        $fileName = "{$this->system->slug}_{$timestamp}";
        $fileNameExt = "{$fileName}.sql";
        $directory = "backups/{$this->system->id}";
        $storagePath = "{$directory}/{$fileNameExt}";
        $fullPath = Storage::path($storagePath);

        // Ensure backup directory exists
        Storage::makeDirectory($directory);

        // Temporary SQL dump path (.part for atomic write)
        $tempSqlPath = $fullPath . '.part';

        // Build mysqldump command
        $command = [
            'mysqldump',
            '--user=' . $this->system->db_username,
            '--host=' . $this->system->db_host,
            '--port=' . $this->system->db_port,
            '--routines',
            '--events',
            '--triggers',
            '--single-transaction',
            '--quick',
            '--opt',
            '--no-tablespaces',
            '--skip-comments',
            $this->system->db_name,
        ];

        // Secure password via env variable (no .cnf file)
        $process = new Process($command, null, [
            'MYSQL_PWD' => $this->system->db_password ?? '',
        ]);
        $process->setTimeout(7200);

        // Open file handle
        $fileHandle = fopen($tempSqlPath, 'w');
        if (!$fileHandle) {
            throw new \RuntimeException("Cannot open temp SQL file: $tempSqlPath");
        }

        // Run dump process
        $process->run(function ($type, $buffer) use ($fileHandle) {
            if ($type === Process::OUT) {
                fwrite($fileHandle, $buffer);
            } elseif ($type === Process::ERR) {
                Log::error("mysqldump process error occurred"); // safe log
            }
        });

        fclose($fileHandle);

        // Handle failure
        if (!$process->isSuccessful()) {
            Storage::delete($tempSqlPath);
            throw new \RuntimeException("Backup failed: " . $process->getErrorOutput());
        }

        // Atomic rename
        rename($tempSqlPath, $fullPath);

        if (!Storage::exists($storagePath)) {
            throw new \RuntimeException("SQL file not found after backup: $fullPath");
        }

        // Save record
        Backup::create([
            'system_id' => $this->system->id,
            'file_name' => $fileNameExt,
            'file_path' => $storagePath,
            'storage_type' => 'local',
            'file_size' => Storage::size($storagePath),
        ]);
    }
}
