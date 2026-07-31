<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'tanggal_expired',
        'stock',
        'harga',
    ];

    protected $casts = [
        'tanggal_expired' => 'date',
        'stock' => 'integer',
        'harga' => 'decimal:2',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    protected function hargaFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->harga, 0, ',', '.'),
        );
    }
}
