<?php

namespace App\Actions;

use App\Models\System;
use Auth;

class CreateSystem
{
    /**
     * Summary of handle
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): System
    {
        $system = System::query()->create([
            'name' => $attributes['name'],
            'db_host' => $attributes['db_host'],
            'db_port' => $attributes['db_port'],
            'db_username' => $attributes['db_username'],
            'db_password' => $attributes['db_password'],
            'db_name' => $attributes['db_name'],
            'created_by' => Auth::id(),
        ]);

        return $system;
    }
}
