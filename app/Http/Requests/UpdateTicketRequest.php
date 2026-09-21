<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            'user_id' => ['prohibited'],
            'status'  => ['required', Rule::in(['open', 'pending', 'closed'])], // Perbaikan dari 'close in' ke 'closed'
        ]);
    }
}