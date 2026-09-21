<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Tambahkan method commonRules ini
    protected function commonRules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'subject'     => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'is_urgent'   => ['required', 'boolean'],
            'note'        => ['required', 'string'],
        ];
    }

    public function rules(): array
    {
        return array_merge($this->commonRules(), [
            'user_id' => ['bail', 'required', 'integer', 'exists:users,id'],
            'status'  => ['prohibited'],
        ]);
    }
}