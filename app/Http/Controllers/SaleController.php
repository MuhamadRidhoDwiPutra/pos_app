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
        try {
            DB::transaction(function () use ($request) {
                $product = Product::where('id', $request->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $request->qty) {
                    throw new \Exception('Stok tidak mencukupi.');
                }

                Sale::create([
                    'product_id' => $product->id,
                    'qty' => $request->qty,
                    'total_harga' => $product->harga * $request->qty,
                ]);

                $product->decrement('stock', $request->qty);
            });
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . Product::find($request->product_id)?->stock ?? 0);
        }

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
