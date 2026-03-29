@extends('layouts.app')

@section('title', 'Subscription Renewal')

@section('content')
    <section class="mx-auto max-w-3xl space-y-6">
        <div class="rounded-[28px] border border-gold/20 bg-[linear-gradient(135deg,rgba(212,175,55,0.12),rgba(255,255,255,0.02))] p-6 shadow-glow sm:p-8">
            <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Subscription status</p>
            <h1 class="mt-4 text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">
                Please renew your subscription
            </h1>
            <p class="mt-4 text-sm leading-7 text-white/65 sm:text-base">
                Your tenant access is currently inactive because the subscription end date has passed. Contact the superadmin to renew your package.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                <p class="text-xs uppercase tracking-[0.18em] text-white/45">Business</p>
                <p class="mt-3 text-lg font-semibold text-ivory">{{ $business->name }}</p>
            </div>

            <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                <p class="text-xs uppercase tracking-[0.18em] text-white/45">Package</p>
                <p class="mt-3 text-lg font-semibold text-gold">{{ $business->subscription_package }}</p>
            </div>

            <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                <p class="text-xs uppercase tracking-[0.18em] text-white/45">Ended on</p>
                <p class="mt-3 text-lg font-semibold text-ivory">
                    {{ optional($business->subscription_ends_at)->format('d M Y') ?: 'Not set' }}
                </p>
            </div>
        </div>
    </section>
@endsection
