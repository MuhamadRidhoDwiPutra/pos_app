@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Supplier</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Supplier</h5>
            <div>
                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">Edit</a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 180px;">No Supplier</th>
                    <td>: {{ $supplier->no_supplier }}</td>
                </tr>
                <tr>
                    <th>Nama PIC</th>
                    <td>: {{ $supplier->nama_pic }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>: {{ $supplier->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Total Pembelian</th>
                    <td>: {{ $supplier->purchases->count() }} transaksi</td>
                </tr>
                <tr>
                    <th>Dibuat</th>
                    <td>: {{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Diperbarui</th>
                    <td>: {{ $supplier->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            @if ($supplier->purchases->count() > 0)
                <hr>
                <h6>Riwayat Pembelian</h6>
                <table class="table table-sm table-striped mt-2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplier->purchases as $purchase)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchase->product->nama_barang }}</td>
                                <td>{{ $purchase->qty }}</td>
                                <td>Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
