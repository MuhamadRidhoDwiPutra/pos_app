<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalSupplier = Supplier::count();
        $totalBarang = Product::count();
        $totalPenjualan = Sale::count();
        $totalRevenue = Sale::sum('total_harga');

        $recentSales = Sale::with('product')->latest()->take(5)->get();

        $chartRaw = Sale::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $row = $chartRaw->get($date);
            $chartData[] = $row ? (float) $row->total : 0;
        }

        return view('dashboard', compact(
            'totalSupplier', 'totalBarang', 'totalPenjualan',
            'totalRevenue', 'recentSales', 'chartLabels', 'chartData'
        ));
    }
}
