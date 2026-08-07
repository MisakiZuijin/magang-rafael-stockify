<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ubah sesuai kebutuhan policy/role
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $this->route('user')],
            'password' => ['nullable', 'string', 'min:8'],
            'role'     => ['required', 'in:Admin,Manager Gudang,Staff Gudang'],
        ];
    }
}
