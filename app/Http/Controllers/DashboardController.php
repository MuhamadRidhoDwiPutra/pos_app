<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalSupplier = Supplier::count();

        $totalBarang = Product::count();

        $totalPenjualan = Sale::count();

        $totalRevenue = Sale::sum('total_harga');

        $recentSales = Sale::with('product')
            ->latest()
            ->take(5)
            ->get();

        $salesChart = Sale::select(
                DB::raw("DATE(created_at) as date"),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');

            $found = $salesChart->firstWhere('date', $date);
            $chartData[] = $found ? (float) $found->total : 0;
        }

        return view('dashboard', compact(
            'totalSupplier',
            'totalBarang',
            'totalPenjualan',
            'totalRevenue',
            'recentSales',
            'chartLabels',
            'chartData',
        ));
    }
}
