<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with('product')
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('sales.create', compact('products'));
    }

    public function store(SaleRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->qty) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        $totalHarga = $product->harga * $request->qty;

        DB::transaction(function () use ($request, $product, $totalHarga) {
            Sale::create([
                'product_id' => $product->id,
                'qty' => $request->qty,
                'total_harga' => $totalHarga,
            ]);

            $product->decrement('stock', $request->qty);
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Penjualan berhasil. Stok barang diperbarui.');
    }

    public function show(Sale $sale): View
    {
        $sale->load('product');

        return view('sales.show', compact('sale'));
    }
}
