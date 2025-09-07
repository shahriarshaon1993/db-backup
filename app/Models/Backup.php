<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id,
 * @property string $file_name
 * @property string $file_path
 * @property string $storage_type
 * @property int $file_size
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read System $systems
 */
class Backup extends Model
{
    public function systems(): HasMany
    {
        return $this->hasMany(System::class);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search)
            return $query;

        $search = "%{$search}%";

        return $query->where(
            fn($q) =>
            $q->where('file_name', 'like', $search)
        );
    }
}
