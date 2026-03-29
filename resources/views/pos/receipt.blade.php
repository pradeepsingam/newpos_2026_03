<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-2xl px-4 py-8">
        <section class="rounded-[28px] bg-white p-6 shadow-xl sm:p-8">
            <div class="flex items-start justify-between gap-6 border-b border-slate-200 pb-6">
                <div class="space-y-3">
                    @if ($sale->business->logo_path)
                        <img src="{{ asset($sale->business->logo_path) }}" alt="Store logo" class="max-h-16 w-auto">
                    @endif

                    <div>
                        <h1 class="text-2xl font-semibold">{{ $sale->business->name }}</h1>
                        <p class="mt-1 text-sm text-slate-500">POS receipt</p>
                    </div>
                </div>

                <div class="text-right text-sm text-slate-500">
                    <p>Receipt #{{ $sale->id }}</p>
                    <p class="mt-1">{{ $sale->created_at->format('d M Y h:i A') }}</p>
                    <p class="mt-1 uppercase">{{ $sale->payment_method }}</p>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between gap-4">
                        <span>Customer</span>
                        <span class="font-medium text-slate-900">{{ $sale->customer_name ?: 'Walking Customer' }}</span>
                    </div>
                    @if ($sale->customer_phone)
                        <div class="mt-2 flex items-center justify-between gap-4">
                            <span>Phone</span>
                            <span class="font-medium text-slate-900">{{ $sale->customer_phone }}</span>
                        </div>
                    @endif
                </div>

                @foreach ($sale->items as $item)
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-3">
                        <div>
                            <p class="font-medium">{{ $item->product->name }}</p>
                            <p class="text-sm text-slate-500">{{ $item->quantity }} x {{ number_format((float) $item->price, 2) }}</p>
                        </div>

                        <p class="text-sm font-semibold">{{ number_format((float) ($item->quantity * $item->price), 2) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 border-t border-slate-200 pt-6">
                <div class="flex items-center justify-between text-sm text-slate-500">
                    <span>Payment</span>
                    <span class="font-medium uppercase text-slate-900">{{ $sale->payment_method }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
                    <span>Subtotal</span>
                    <span class="font-medium text-slate-900">{{ number_format((float) $sale->subtotal_amount, 2) }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
                    <span>Redeemed points</span>
                    <span class="font-medium text-slate-900">{{ $sale->redeemed_points }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
                    <span>Earned points</span>
                    <span class="font-medium text-slate-900">{{ $sale->earned_points }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
                    <span>Amount paid</span>
                    <span class="font-medium text-slate-900">{{ number_format((float) $sale->amount_paid, 2) }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
                    <span>Balance to give</span>
                    <span class="font-medium text-slate-900">{{ number_format((float) $sale->balance_amount, 2) }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-lg font-semibold">Total</span>
                    <span class="text-2xl font-bold">{{ number_format((float) $sale->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="mt-8 flex gap-3 print:hidden">
                <button
                    type="button"
                    onclick="window.print()"
                    class="flex-1 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white"
                >
                    Print Bill
                </button>
                <button
                    type="button"
                    onclick="window.close()"
                    class="flex-1 rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700"
                >
                    Close
                </button>
            </div>
        </section>
    </main>

    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</body>
</html>
