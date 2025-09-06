<?php

namespace App\Http\Resources;

use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin System
 */
class SystemResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'db_host' => $this->db_host,
            'db_port' => $this->db_port,
            'db_username' => $this->db_username,
            'db_password' => $this->db_password,
            'created_by' => $this->createdBy->name,
            'created_at' => $this->created_at->format('Y-d-m'),
            'backups' => BackupResource::collection($this->whenLoaded('backups'))
        ];
    }
}
