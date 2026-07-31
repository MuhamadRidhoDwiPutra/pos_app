@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Barang</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Barang</h5>
            <div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 180px;">Kode Barang</th>
                    <td>: <span class="badge bg-secondary">{{ $product->kode_barang }}</span></td>
                </tr>
                <tr>
                    <th>Nama Barang</th>
                    <td>: {{ $product->nama_barang }}</td>
                </tr>
                <tr>
                    <th>Stock</th>
                    <td>: {{ $product->stock }} unit</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>: Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tanggal Expired</th>
                    <td>:
                        @if ($product->tanggal_expired)
                            {{ $product->tanggal_expired->format('d/m/Y') }}
                            @if ($product->tanggal_expired->isPast())
                                <span class="badge bg-danger ms-1">Expired</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Dibuat</th>
                    <td>: {{ $product->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Diperbarui</th>
                    <td>: {{ $product->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            @if ($product->purchases->count() > 0 || $product->sales->count() > 0)
                <hr>
                <div class="row">
                    @if ($product->purchases->count() > 0)
                        <div class="col-md-6">
                            <h6>Riwayat Pembelian</h6>
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->purchases as $purchase)
                                        <tr>
                                            <td>{{ $purchase->supplier->nama_pic }}</td>
                                            <td>{{ $purchase->qty }}</td>
                                            <td>Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if ($product->sales->count() > 0)
                        <div class="col-md-6">
                            <h6>Riwayat Penjualan</h6>
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->sales as $sale)
                                        <tr>
                                            <td>{{ $sale->qty }}</td>
                                            <td>Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
