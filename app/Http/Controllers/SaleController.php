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
        $sales = Sale::with('product')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $products = Product::where('stock', '>', 0)->orderBy('nama_barang')->get();
        return view('sales.create', compact('products'));
    }

    public function store(SaleRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $product = Product::where('id', $request->product_id)->lockForUpdate()->firstOrFail();

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
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil disimpan.');
    }

    public function show(Sale $sale): View
    {
        $sale->load('product');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale): View
    {
        $products = Product::where('stock', '>', 0)->orWhere('id', $sale->product_id)->orderBy('nama_barang')->get();
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(SaleRequest $request, Sale $sale): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $sale) {
                $product = Product::where('id', $sale->product_id)->first();
                $newProduct = Product::where('id', $request->product_id)->lockForUpdate()->first();
                $oldQty = $sale->qty;

                if ($sale->product_id == $request->product_id) {
                    $diff = $request->qty - $oldQty;
                    if ($diff > 0) {
                        if ($newProduct->stock < $diff) {
                            throw new \Exception('Stok tidak mencukupi.');
                        }
                        $newProduct->decrement('stock', $diff);
                    } elseif ($diff < 0) {
                        $newProduct->increment('stock', abs($diff));
                    }
                } else {
                    $product->increment('stock', $oldQty);
                    if ($newProduct->stock < $request->qty) {
                        throw new \Exception('Stok tidak mencukupi.');
                    }
                    $newProduct->decrement('stock', $request->qty);
                }

                $sale->update([
                    'product_id' => $request->product_id,
                    'qty' => $request->qty,
                    'total_harga' => $newProduct->harga * $request->qty,
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil diperbarui.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        DB::transaction(function () use ($sale) {
            $product = Product::where('id', $sale->product_id)->first();
            if ($product) {
                $product->increment('stock', $sale->qty);
            }
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus.');
    }
}
