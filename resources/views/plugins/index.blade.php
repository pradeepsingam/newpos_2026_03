@extends('layouts.app')

@section('title', 'Plugins')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Platform extensions</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">Plugin Control Center</h1>
                    <p class="mt-3 text-sm leading-7 text-white/60 sm:text-base">
                        Superadmins upload approved packages globally and roll out a specific approved version to each tenant independently.
                    </p>
                </div>
            </div>
        </div>

        @if (auth()->user()->hasPlatformAccess())
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl sm:p-6">
                <div class="mb-5">
                    <p class="text-xs uppercase tracking-[0.18em] text-gold/80">Platform upload</p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-[0.05em] text-ivory">Approve plugin package</h2>
                </div>

                <form method="POST" action="{{ route('plugins.upload') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="plugin_zip" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Signed ZIP package</label>
                        <input
                            id="plugin_zip"
                            name="plugin_zip"
                            type="file"
                            accept=".zip"
                            required
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-white/75 file:mr-4 file:rounded-xl file:border-0 file:bg-gold file:px-4 file:py-2 file:text-sm file:font-semibold file:text-black"
                        >
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-black/25 p-4 text-sm leading-7 text-white/60">
                        <p class="font-medium text-ivory">Approval rules</p>
                        <p class="mt-2">Each package must include a signed <code class="text-gold">plugin.json</code>, a provider class, and any declared routes, views, or migrations.</p>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-gold px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-black transition hover:bg-[#e6c766]"
                    >
                        Upload Approved Package
                    </button>
                </form>
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($plugins as $entry)
                @php($plugin = $entry['plugin'])
                <div class="rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl sm:p-6">
                    <div class="space-y-5">
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-2xl font-semibold tracking-[0.04em] text-ivory">{{ $plugin->name }}</h2>
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] uppercase tracking-[0.16em] text-white/55">
                                    {{ $plugin->slug }}
                                </span>
                            </div>

                            <p class="max-w-3xl text-sm leading-7 text-white/60">{{ $plugin->description ?: 'No description provided.' }}</p>
                        </div>

                        <div class="grid gap-4 xl:grid-cols-[0.7fr_1.3fr]">
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-gold/75">Approved versions</p>
                                <div class="mt-4 space-y-3">
                                    @forelse ($entry['versions'] as $version)
                                        <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                            <div class="flex items-center justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-semibold text-ivory">v{{ $version->version }}</p>
                                                    <p class="text-xs text-white/45">{{ $version->provider_class }}</p>
                                                </div>
                                                <span class="rounded-full border px-3 py-1 text-[11px] uppercase tracking-[0.16em] {{ $version->is_approved ? 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300' : 'border-white/10 bg-white/5 text-white/45' }}">
                                                    {{ $version->is_approved ? 'Approved' : 'Pending' }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-white/45">No approved versions yet.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-gold/75">Tenant rollout</p>
                                <div class="mt-4 space-y-4">
                                    @foreach ($entry['businesses'] as $businessEntry)
                                        @php($business = $businessEntry['business'])
                                        @php($assignment = $businessEntry['assignment'])
                                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold text-ivory">{{ $business->name }}</p>
                                                    <p class="mt-1 text-xs text-white/45">
                                                        Status: {{ $assignment?->status ?? 'inactive' }}
                                                        @if ($assignment?->version)
                                                            | Version: {{ $assignment->version->version }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="flex w-full flex-col gap-3 lg:w-auto lg:min-w-[320px]">
                                                    <form method="POST" action="{{ route('plugins.assign', $plugin) }}" class="flex flex-col gap-3 sm:flex-row">
                                                        @csrf
                                                        <input type="hidden" name="business_id" value="{{ $business->id }}">

                                                        <select
                                                            name="plugin_version_id"
                                                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-ivory outline-none"
                                                        >
                                                            @foreach ($entry['versions']->where('is_approved', true) as $version)
                                                                <option value="{{ $version->id }}" @selected($assignment?->plugin_version_id === $version->id)>
                                                                    v{{ $version->version }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <button
                                                            type="submit"
                                                            class="rounded-xl bg-gold px-4 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-black transition hover:bg-[#e6c766]"
                                                        >
                                                            Enable
                                                        </button>
                                                    </form>

                                                    @if ($assignment && $assignment->status === 'active')
                                                        <form method="POST" action="{{ route('plugins.deactivate', $plugin) }}">
                                                            @csrf
                                                            <input type="hidden" name="business_id" value="{{ $business->id }}">
                                                            <button
                                                                type="submit"
                                                                class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-white/75 transition hover:border-gold/40 hover:text-gold"
                                                            >
                                                                Disable For Tenant
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-[28px] border border-dashed border-white/15 bg-white/5 px-4 py-12 text-center backdrop-blur-xl">
                    <p class="text-base font-medium text-ivory">No plugins uploaded yet.</p>
                    <p class="mt-2 text-sm text-white/50">Superadmins can upload an approved package to begin tenant rollout.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
