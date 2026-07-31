<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('supplier')?->id;
        return [
            'no_supplier' => [
                'required',
                'string',
                'max:50',
                $id ? "unique:suppliers,no_supplier,{$id}" : 'unique:suppliers,no_supplier',
            ],
            'nama_pic' => 'required|string|max:100',
            'alamat' => 'nullable|string',
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
