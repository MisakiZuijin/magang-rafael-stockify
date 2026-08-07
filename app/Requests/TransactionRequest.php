<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ubah sesuai kebutuhan policy/role
    }

    public function rules(): array
    {
        return [
            'product_id'    => ['required', 'bigint', 'min:0'],
            'user_id'       => ['required', 'bigint', 'min:0'],
            'type'          => ['required', 'enum', 'min:0'],
            'quantity'          => ['required', 'integer', 'max:0'],
            'date'         => ['required', 'date', 'min:0'],
            'status'         => ['required', 'enum', 'min:0'],
            'note'         => ['required', 'text', 'min:255'],
        ];
    }
}
