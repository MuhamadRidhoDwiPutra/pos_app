@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Data Barang</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            + Tambah Barang
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Expired</th>
                        <th>Stock</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $products->firstItem() + $loop->index }}</td>
                            <td><span class="badge bg-secondary">{{ $product->kode_barang }}</span></td>
                            <td>{{ $product->nama_barang }}</td>
                            <td>
                                @if ($product->tanggal_expired)
                                    {{ $product->tanggal_expired->format('d/m/Y') }}
                                    @if ($product->tanggal_expired->isPast())
                                        <span class="badge bg-danger">Expired</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($product->stock < 5)
                                    <span class="text-danger fw-bold">{{ $product->stock }}</span>
                                @else
                                    {{ $product->stock }}
                                @endif
                            </td>
                            <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">Belum ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
