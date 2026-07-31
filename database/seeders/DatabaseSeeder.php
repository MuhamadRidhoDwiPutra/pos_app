<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pos.app'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $suppliers = [
            ['no_supplier' => 'SUP-001', 'nama_pic' => 'PT Maju Jaya', 'alamat' => 'Jl. Raya Surabaya No. 10'],
            ['no_supplier' => 'SUP-002', 'nama_pic' => 'CV Berkah Sentosa', 'alamat' => 'Jl. Pemuda No. 25, Semarang'],
        ];

        foreach ($suppliers as $s) {
            Supplier::updateOrCreate(['no_supplier' => $s['no_supplier']], $s);
        }

        $products = [
            ['kode_barang' => 'BRG-001', 'nama_barang' => 'Mie Instan', 'tanggal_expired' => now()->addMonths(6), 'stock' => 50, 'harga' => 3500],
            ['kode_barang' => 'BRG-002', 'nama_barang' => 'Sabun Mandi', 'tanggal_expired' => now()->addMonths(12), 'stock' => 30, 'harga' => 5000],
            ['kode_barang' => 'BRG-003', 'nama_barang' => 'Shampo Botol', 'tanggal_expired' => now()->addMonths(8), 'stock' => 25, 'harga' => 15000],
            ['kode_barang' => 'BRG-004', 'nama_barang' => 'Minuman Kemasan', 'tanggal_expired' => now()->addMonths(3), 'stock' => 40, 'harga' => 4000],
            ['kode_barang' => 'BRG-005', 'nama_barang' => 'Biskuit', 'tanggal_expired' => now()->addMonths(4), 'stock' => 35, 'harga' => 8000],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['kode_barang' => $p['kode_barang']], $p);
        }

        $supplier1 = Supplier::where('no_supplier', 'SUP-001')->first();
        $supplier2 = Supplier::where('no_supplier', 'SUP-002')->first();
        $mie = Product::where('kode_barang', 'BRG-001')->first();
        $sabun = Product::where('kode_barang', 'BRG-002')->first();
        $shampo = Product::where('kode_barang', 'BRG-003')->first();
        $minuman = Product::where('kode_barang', 'BRG-004')->first();
        $biskuit = Product::where('kode_barang', 'BRG-005')->first();

        if ($supplier1 && $mie && !$mie->purchases()->exists()) {
            Purchase::create([
                'supplier_id' => $supplier1->id,
                'product_id' => $mie->id,
                'qty' => 50,
                'total_harga' => $mie->harga * 50,
            ]);
        }

        if ($supplier2 && $sabun && !$sabun->purchases()->exists()) {
            Purchase::create([
                'supplier_id' => $supplier2->id,
                'product_id' => $sabun->id,
                'qty' => 30,
                'total_harga' => $sabun->harga * 30,
            ]);
        }

        if ($shampo && !$shampo->sales()->exists()) {
            Sale::create([
                'product_id' => $shampo->id,
                'qty' => 5,
                'total_harga' => $shampo->harga * 5,
            ]);
            $shampo->decrement('stock', 5);
        }

        if ($minuman && !$minuman->sales()->exists()) {
            Sale::create([
                'product_id' => $minuman->id,
                'qty' => 10,
                'total_harga' => $minuman->harga * 10,
            ]);
            $minuman->decrement('stock', 10);
        }

        if ($biskuit && !$biskuit->sales()->exists()) {
            Sale::create([
                'product_id' => $biskuit->id,
                'qty' => 8,
                'total_harga' => $biskuit->harga * 8,
            ]);
            $biskuit->decrement('stock', 8);
        }
    }
}
