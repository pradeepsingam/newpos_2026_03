@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl sm:p-8">
            <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Customer directory</p>
            <div class="mt-4 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h1 class="text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">Customers</h1>
                    <p class="mt-3 text-sm leading-7 text-white/60 sm:text-base">
                        Every non-walking customer saved during POS checkout appears here automatically.
                    </p>
                </div>

                <form method="GET" action="{{ route('customers.index') }}" class="w-full max-w-md">
                    <label for="search" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/45">Search customers</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ $search }}"
                        placeholder="Search by name or phone"
                        class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-4 text-sm text-ivory outline-none placeholder:text-white/25"
                    >
                </form>
            </div>
        </div>

        <div class="rounded-[28px] border border-white/10 bg-white/5 shadow-glow backdrop-blur-xl">
            <div class="divide-y divide-white/10">
                @forelse ($customers as $customer)
                    <div class="px-5 py-4 sm:px-6">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-base font-semibold text-ivory">{{ $customer->name }}</p>
                                <p class="mt-1 text-sm text-white/45">{{ $customer->phone ?: 'No phone number saved' }}</p>
                            </div>

                            <div class="text-sm text-white/50 sm:text-right">
                                <p class="text-gold">{{ $customer->points_balance }} points</p>
                                <p>{{ $customer->sales_count }} sale{{ $customer->sales_count === 1 ? '' : 's' }}</p>
                                <p class="mt-1">{{ $customer->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center sm:px-6">
                        <p class="text-base font-medium text-ivory">No saved customers yet.</p>
                        <p class="mt-2 text-sm text-white/50">Complete a POS sale with customer details to populate this list.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{ $customers->links() }}
    </section>
@endsection
