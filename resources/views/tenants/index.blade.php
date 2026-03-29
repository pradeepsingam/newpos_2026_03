@extends('layouts.app')

@section('title', 'Tenants')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Tenant management</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">Tenant Control Center</h1>
                    <p class="mt-3 text-sm leading-7 text-white/60 sm:text-base">
                        Create businesses, assign owner accounts, and review active tenants from one place.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl sm:p-6">
                <div class="mb-5">
                    <p class="text-xs uppercase tracking-[0.18em] text-gold/80">Tenant onboarding</p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-[0.05em] text-ivory">Add new tenant</h2>
                    <p class="mt-2 text-sm leading-7 text-white/55">Create a business and its owner account in one step.</p>
                </div>

                <form method="POST" action="{{ route('tenants.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="business_name" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Business name</label>
                        <input
                            id="business_name"
                            name="business_name"
                            type="text"
                            value="{{ old('business_name') }}"
                            required
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none placeholder:text-white/25"
                        >
                        @error('business_name')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="owner_name" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Owner name</label>
                            <input
                                id="owner_name"
                                name="owner_name"
                                type="text"
                                value="{{ old('owner_name') }}"
                                required
                                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none placeholder:text-white/25"
                            >
                            @error('owner_name')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="owner_email" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Owner email</label>
                            <input
                                id="owner_email"
                                name="owner_email"
                                type="email"
                                value="{{ old('owner_email') }}"
                                required
                                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none placeholder:text-white/25"
                            >
                            @error('owner_email')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="owner_password" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Password</label>
                            <input
                                id="owner_password"
                                name="owner_password"
                                type="password"
                                required
                                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                            >
                            @error('owner_password')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="owner_password_confirmation" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Confirm password</label>
                            <input
                                id="owner_password_confirmation"
                                name="owner_password_confirmation"
                                type="password"
                                required
                                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="subscription_package" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Package</label>
                        <input
                            id="subscription_package"
                            name="subscription_package"
                            type="text"
                            value="{{ old('subscription_package', 'Starter') }}"
                            required
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none placeholder:text-white/25"
                        >
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="subscription_starts_at" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Start date</label>
                            <input
                                id="subscription_starts_at"
                                name="subscription_starts_at"
                                type="date"
                                value="{{ old('subscription_starts_at', now()->toDateString()) }}"
                                required
                                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none [color-scheme:dark]"
                            >
                        </div>

                        <div>
                            <label for="subscription_ends_at" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">End date</label>
                            <input
                                id="subscription_ends_at"
                                name="subscription_ends_at"
                                type="date"
                                value="{{ old('subscription_ends_at', now()->addDays(30)->toDateString()) }}"
                                required
                                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none [color-scheme:dark]"
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-gold px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-black transition hover:bg-[#e6c766]"
                    >
                        Create Tenant
                    </button>
                </form>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl sm:p-6">
                <div class="mb-5">
                    <p class="text-xs uppercase tracking-[0.18em] text-gold/80">Tenant list</p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-[0.05em] text-ivory">Active businesses</h2>
                </div>

                <div class="space-y-3">
                    @forelse ($businesses as $business)
                        <div class="rounded-2xl border border-white/10 bg-black/25 p-4">
                            <div class="space-y-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-ivory">{{ $business->name }}</p>
                                        <p class="mt-1 text-xs text-gold/80">
                                            {{ $business->subscription_package }} package
                                        </p>
                                        <p class="mt-1 text-xs text-white/50">
                                            Owner: {{ $business->owner?->name ?? 'Not assigned' }}
                                        </p>
                                        <p class="mt-1 text-xs text-white/40">
                                            {{ $business->owner?->email ?? 'No owner email' }}
                                        </p>
                                        <p class="mt-2 text-xs text-white/45">
                                            {{ optional($business->subscription_starts_at)->format('d M Y') ?: 'Not set' }}
                                            -
                                            {{ optional($business->subscription_ends_at)->format('d M Y') ?: 'Not set' }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col items-end gap-2">
                                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] uppercase tracking-[0.16em] text-white/55">
                                            {{ $business->users_count }} users
                                        </span>
                                        <span class="rounded-full border px-3 py-1 text-[11px] uppercase tracking-[0.16em] {{ $business->is_active ? 'border-emerald-400/30 bg-emerald-500/10 text-emerald-200' : 'border-rose-400/30 bg-rose-500/10 text-rose-200' }}">
                                            {{ $business->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>

                                <details class="rounded-xl border border-white/10 bg-white/5">
                                    <summary class="cursor-pointer list-none px-4 py-3 text-sm font-medium text-white/75">
                                        Edit business
                                    </summary>

                                    <div class="border-t border-white/10 p-4">
                                        <form method="POST" action="{{ route('tenants.update', $business) }}" class="space-y-4">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label for="business_name_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Business name</label>
                                                <input
                                                    id="business_name_{{ $business->id }}"
                                                    name="business_name"
                                                    type="text"
                                                    value="{{ old('business_name', $business->name) }}"
                                                    required
                                                    class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                                                >
                                            </div>

                                            <div class="grid gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="owner_name_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Owner name</label>
                                                    <input
                                                        id="owner_name_{{ $business->id }}"
                                                        name="owner_name"
                                                        type="text"
                                                        value="{{ old('owner_name', $business->owner?->name) }}"
                                                        required
                                                        class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                                                    >
                                                </div>

                                                <div>
                                                    <label for="owner_email_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Owner email</label>
                                                    <input
                                                        id="owner_email_{{ $business->id }}"
                                                        name="owner_email"
                                                        type="email"
                                                        value="{{ old('owner_email', $business->owner?->email) }}"
                                                        required
                                                        class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                                                    >
                                                </div>
                                            </div>

                                            <div class="grid gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="owner_password_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">New password</label>
                                                    <input
                                                        id="owner_password_{{ $business->id }}"
                                                        name="owner_password"
                                                        type="password"
                                                        class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                                                    >
                                                </div>

                                                <div>
                                                    <label for="owner_password_confirmation_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Confirm password</label>
                                                    <input
                                                        id="owner_password_confirmation_{{ $business->id }}"
                                                        name="owner_password_confirmation"
                                                        type="password"
                                                        class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                                                    >
                                                </div>
                                            </div>

                                            <div>
                                                <label for="subscription_package_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Package</label>
                                                <input
                                                    id="subscription_package_{{ $business->id }}"
                                                    name="subscription_package"
                                                    type="text"
                                                    value="{{ old('subscription_package', $business->subscription_package) }}"
                                                    required
                                                    class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none"
                                                >
                                            </div>

                                            <div class="grid gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="subscription_starts_at_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Start date</label>
                                                    <input
                                                        id="subscription_starts_at_{{ $business->id }}"
                                                        name="subscription_starts_at"
                                                        type="date"
                                                        value="{{ old('subscription_starts_at', optional($business->subscription_starts_at)->toDateString()) }}"
                                                        required
                                                        class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none [color-scheme:dark]"
                                                    >
                                                </div>

                                                <div>
                                                    <label for="subscription_ends_at_{{ $business->id }}" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">End date</label>
                                                    <input
                                                        id="subscription_ends_at_{{ $business->id }}"
                                                        name="subscription_ends_at"
                                                        type="date"
                                                        value="{{ old('subscription_ends_at', optional($business->subscription_ends_at)->toDateString()) }}"
                                                        required
                                                        class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-ivory outline-none [color-scheme:dark]"
                                                    >
                                                </div>
                                            </div>

                                            <div class="flex flex-col gap-3 sm:flex-row">
                                                <button
                                                    type="submit"
                                                    class="w-full rounded-xl bg-gold px-4 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-black transition hover:bg-[#e6c766]"
                                                >
                                                    Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </details>

                                <form method="POST" action="{{ route('tenants.destroy', $business) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        onclick="return confirm('Delete {{ $business->name }}? This only works if there is no business data yet.')"
                                        class="w-full rounded-xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-medium text-rose-200 transition hover:border-rose-400/40 hover:bg-rose-500/15"
                                    >
                                        Delete Business
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-dashed border-white/10 bg-black/20 px-4 py-6 text-sm text-white/45">
                            No tenants created yet.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
