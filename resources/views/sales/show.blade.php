@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
    <div class="space-y-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Sale #{{ $sale->id }}</h1>
                <p class="text-sm text-slate-500">{{ $sale->created_at?->format('d M Y, h:i A') }}</p>
            </div>

            <a
                href="{{ route('sales.index') }}"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700"
            >
                Back
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Subtotal</p>
                    <p class="text-base font-semibold text-slate-900">{{ number_format((float) $sale->subtotal_amount, 2) }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Total Amount</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format((float) $sale->total_amount, 2) }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Payment</p>
                    <p class="text-base font-semibold uppercase text-slate-900">{{ $sale->payment_method }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Customer</p>
                    <p class="text-base font-semibold text-slate-900">{{ $sale->customer_name ?: 'Walking Customer' }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Phone</p>
                    <p class="text-base font-semibold text-slate-900">{{ $sale->customer_phone ?: '-' }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Redeemed Points</p>
                    <p class="text-base font-semibold text-slate-900">{{ $sale->redeemed_points }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Earned Points</p>
                    <p class="text-base font-semibold text-slate-900">{{ $sale->earned_points }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Amount Paid</p>
                    <p class="text-base font-semibold text-slate-900">{{ number_format((float) $sale->amount_paid, 2) }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Balance to Give</p>
                    <p class="text-base font-semibold text-slate-900">{{ number_format((float) $sale->balance_amount, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="text-lg font-semibold text-slate-900">Sale Items</h2>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse ($sale->items as $item)
                    <div class="px-4 py-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ $item->product?->name ?? 'Product removed' }}</p>
                                <p class="text-sm text-slate-500">Qty: {{ $item->quantity }}</p>
                            </div>

                            <div class="flex items-center justify-between gap-4 sm:block sm:text-right">
                                <p class="text-sm text-slate-500">{{ number_format((float) $item->price, 2) }} each</p>
                                <p class="text-base font-semibold text-slate-900">
                                    {{ number_format((float) ($item->price * $item->quantity), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-sm text-slate-500">
                        No items found for this sale.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
