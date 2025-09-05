<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSystemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:30', 'unique:systems,name'],
            'db_name' => ['required', 'string', 'min:3', 'max:30'],
            'db_host' => ['nullable', 'string', 'min:3', 'max:30'],
            'db_port' => ['nullable', 'integer'],
            'db_username' => ['required', 'string', 'min:3', 'max:30'],
            'db_password' => ['required', 'string', 'min:3', 'max:30'],
        ];
    }
}
