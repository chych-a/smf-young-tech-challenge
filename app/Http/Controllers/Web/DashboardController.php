<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $activity = collect(range(6, 1))->map(function (int $daysAgo): array {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->format('d.m'),
                'count' => Invoice::query()->whereDate('created_at', $date)->count(),
            ];
        })->push([
            'label' => Carbon::today()->format('d.m'),
            'count' => Invoice::query()->whereDate('created_at', Carbon::today())->count(),
        ]);

        return view('dashboard.index', [
            'productCount' => Product::query()->count(),
            'invoiceCount' => Invoice::query()->count(),
            'itemCount' => InvoiceItem::query()->count(),
            'totalAmount' => Invoice::query()->sum('total_amount'),
            'recentInvoices' => Invoice::query()
                ->with('contractor')
                ->latest()
                ->limit(5)
                ->get(),
            'recentProducts' => Product::query()
                ->latest()
                ->limit(5)
                ->get(),
            'ocrActivity' => $activity,
            'maxActivity' => max(1, (int) $activity->max('count')),
        ]);
    }
}
