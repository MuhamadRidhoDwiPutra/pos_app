<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Barang wajib dipilih.',
            'product_id.exists' => 'Barang tidak ditemukan.',
            'qty.required' => 'Qty wajib diisi.',
            'qty.integer' => 'Qty harus berupa angka.',
            'qty.min' => 'Qty minimal 1.',
        ];
    }
}
