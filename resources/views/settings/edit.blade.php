@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl sm:p-8">
            <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Store settings</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">Brand your receipts</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/60 sm:text-base">
                Update your store name and logo. The uploaded logo will appear on printed bills.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl">
                <p class="text-xs uppercase tracking-[0.18em] text-gold/75">Current logo</p>

                <div class="mt-4 flex min-h-[240px] items-center justify-center rounded-[24px] border border-dashed border-white/10 bg-black/20 p-6">
                    @if ($business->logo_path)
                        <img src="{{ asset($business->logo_path) }}" alt="Store logo" class="max-h-40 w-auto rounded-2xl bg-white p-3">
                    @else
                        <p class="text-center text-sm text-white/45">No logo uploaded yet.</p>
                    @endif
                </div>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl">
                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Store name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $business->name) }}"
                            required
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                        >
                    </div>

                    <div>
                        <label for="logo" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Store logo</label>
                        <input
                            id="logo"
                            name="logo"
                            type="file"
                            accept="image/*"
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-white/75 file:mr-4 file:rounded-xl file:border-0 file:bg-gold file:px-4 file:py-2 file:text-sm file:font-semibold file:text-black"
                        >
                        <p class="mt-2 text-sm text-white/45">PNG, JPG, or WEBP. Maximum 2 MB.</p>
                    </div>

                    <div>
                        <label for="points_percentage" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Points percentage</label>
                        <input
                            id="points_percentage"
                            name="points_percentage"
                            type="number"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ old('points_percentage', $business->points_percentage) }}"
                            required
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                        >
                        <p class="mt-2 text-sm text-white/45">Example: `5` means the customer earns 5 points for every 100 worth of purchase.</p>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-gold px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-black transition hover:bg-[#e6c766]"
                    >
                        Save Settings
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
