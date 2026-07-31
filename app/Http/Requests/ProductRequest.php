<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        $rules = [
            'kode_barang' => [
                'required',
                'string',
                'max:50',
                $productId
                    ? 'unique:products,kode_barang,' . $productId
                    : 'unique:products,kode_barang',
            ],
            'nama_barang' => ['required', 'string', 'max:150'],
            'tanggal_expired' => ['nullable', 'date', 'after_or_equal:today'],
            'stock' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'tanggal_expired.after_or_equal' => 'Tanggal expired harus hari ini atau setelahnya.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
        ];
    }
}
