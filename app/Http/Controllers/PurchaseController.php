<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = Purchase::with(['supplier', 'product'])->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('nama_pic')->get();
        $products = Product::orderBy('nama_barang')->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(PurchaseRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $product = Product::where('id', $request->product_id)->lockForUpdate()->firstOrFail();
            Purchase::create([
                'supplier_id' => $request->supplier_id,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'total_harga' => $product->harga * $request->qty,
            ]);
            $product->increment('stock', $request->qty);
        });

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'product']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase): View
    {
        $suppliers = Supplier::orderBy('nama_pic')->get();
        $products = Product::orderBy('nama_barang')->get();
        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(PurchaseRequest $request, Purchase $purchase): RedirectResponse
    {
        DB::transaction(function () use ($request, $purchase) {
            $product = Product::where('id', $purchase->product_id)->first();
            $newProduct = Product::where('id', $request->product_id)->lockForUpdate()->first();
            $oldQty = $purchase->qty;

            if ($purchase->product_id == $request->product_id) {
                $diff = $request->qty - $oldQty;
                if ($diff > 0) {
                    $product->increment('stock', $diff);
                } elseif ($diff < 0) {
                    $product->decrement('stock', abs($diff));
                }
            } else {
                $product->increment('stock', $oldQty);
                $newProduct->decrement('stock', $request->qty);
            }

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'total_harga' => $newProduct->harga * $request->qty,
            ]);
        });

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil diperbarui.');
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        DB::transaction(function () use ($purchase) {
            $product = Product::where('id', $purchase->product_id)->first();
            if ($product) {
                $product->increment('stock', $purchase->qty);
            }
            $purchase->delete();
        });

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
