<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150',
                        Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}