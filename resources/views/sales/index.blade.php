@extends('layouts.app')

@section('title', 'Sales History')

@section('content')
    <div class="space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Sales History</h1>
                <p class="text-sm text-slate-500">Track completed bills for your business.</p>
            </div>

            <form method="GET" action="{{ route('sales.index') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div>
                    <label for="date" class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Date</label>
                    <input
                        id="date"
                        name="date"
                        type="date"
                        value="{{ $selectedDate }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none [color-scheme:light] focus:border-slate-500"
                    >
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route('sales.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">Daily Total Sales</p>
            <div class="mt-2 flex items-end justify-between gap-3">
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ number_format((float) $dailyTotal, 2) }}</p>
                    <p class="text-sm text-slate-500">{{ \Illuminate\Support\Carbon::parse($reportDate)->format('d M Y') }}</p>
                </div>
                <a
                    href="{{ route('pos.index') }}"
                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700"
                >
                    New Sale
                </a>
            </div>
        </div>

        @if ($sales->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center">
                <p class="text-base font-medium text-slate-800">No sales found.</p>
                <p class="mt-2 text-sm text-slate-500">Completed sales will appear here.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($sales as $sale)
                    <a
                        href="{{ route('sales.show', $sale) }}"
                        class="block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-slate-400"
                    >
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="space-y-1">
                                <p class="text-base font-semibold text-slate-900">Sale #{{ $sale->id }}</p>
                                <p class="text-sm text-slate-500">{{ $sale->created_at?->format('d M Y, h:i A') }}</p>
                                <p class="text-sm text-slate-500">{{ $sale->items_count }} item(s)</p>
                            </div>

                            <div class="flex items-center justify-between gap-4 sm:block sm:text-right">
                                <p class="text-lg font-bold text-slate-900">{{ number_format((float) $sale->total_amount, 2) }}</p>
                                <p class="text-sm text-slate-500">View details</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div>
                {{ $sales->links() }}
            </div>
        @endif
    </div>
@endsection
