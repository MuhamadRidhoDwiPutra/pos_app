<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier');

        return [
            'no_supplier' => [
                'required',
                'string',
                'max:50',
                'unique:suppliers,no_supplier,' . $supplierId,
            ],
            'nama_pic' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_supplier.required' => 'Nomor supplier wajib diisi.',
            'no_supplier.unique' => 'Nomor supplier sudah digunakan.',
            'nama_pic.required' => 'Nama PIC wajib diisi.',
        ];
    }
}
