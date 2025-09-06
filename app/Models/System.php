<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $db_host
 * @property string $db_port
 * @property string $db_username
 * @property string $db_password
 * @property string $db_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $createdBy
 * @property-read Backup $backups
 */
class System extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($system) {
            $system->slug = Str::slug($system->name);
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class);
    }
}
