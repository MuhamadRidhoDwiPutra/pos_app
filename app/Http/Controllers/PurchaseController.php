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
        $purchases = Purchase::with(['supplier', 'product'])
            ->latest()
            ->paginate(10);

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
            $product = Product::where('id', $request->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            Purchase::create([
                'supplier_id' => $request->supplier_id,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'total_harga' => $product->harga * $request->qty,
            ]);

            $product->increment('stock', $request->qty);
        });

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Pembelian berhasil. Stok barang diperbarui.');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'product']);

        return view('purchases.show', compact('purchase'));
    }
}
