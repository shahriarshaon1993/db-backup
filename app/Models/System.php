<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $db_host
 * @property string $db_port
 * @property string $db_username
 * @property string $db_password
 * @property string $db_name
 * @property-read User $createdBy
 */
class System extends Model
{
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
