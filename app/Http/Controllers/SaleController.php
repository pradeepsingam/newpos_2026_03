<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->string('date')->toString();
        $date = $selectedDate !== '' ? $selectedDate : now()->toDateString();

        $sales = Sale::query()
            ->withCount('items')
            ->when($selectedDate !== '', function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $dailyTotal = Sale::query()
            ->whereDate('created_at', $date)
            ->sum('total_amount');

        return view('sales.index', [
            'sales' => $sales,
            'dailyTotal' => $dailyTotal,
            'selectedDate' => $selectedDate,
            'reportDate' => $date,
        ]);
    }

    public function show(Sale $sale): View
    {
        $sale->load('items.product');

        return view('sales.show', [
            'sale' => $sale,
        ]);
    }
}
