<?php

namespace App\Http\Resources;

use App\Helpers\FileSizeHelper;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Backup
 */
class BackupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'storage_type' => $this->storage_type,
            'file_size' => FileSizeHelper::formatFileSize($this->file_size),
            'created_at' => $this->created_at->toDayDateTimeString(),
        ];
    }
}
