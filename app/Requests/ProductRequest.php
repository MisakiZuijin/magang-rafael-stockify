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
            'category_id'    => ['required', 'integer', 'exists:categories,id'],
            'supplier_id'    => ['required', 'integer', 'exists:suppliers,id'],
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'sku'            => ['required', 'string', 'max:100', 'unique:products,sku'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'      => 'Category produk wajib diisi.',
            'suplier_id.required'       => 'Suplier wajib diisi.',
            'name.required'             => 'Nama produk wajib diisi.',
            'description.required'      => 'Nama produk wajib diisi.',
            'sku.required'              => 'Nama produk wajib diisi.',
            'purchase_price.required'   => 'Harga beli wajib diisi.',
            'selling_price.required'    => 'Harga jual wajib diisi.',
            'image.required'            => 'Harga jual wajib diisi.',
            'stock.required'            => 'Stok wajib diisi.',
            'stock.min'                 => 'Stok tidak boleh negatif.',
        ];
    }
}
