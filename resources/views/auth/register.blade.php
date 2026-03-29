@extends('layouts.auth')

@section('title', 'Register')
@section('heading', 'Create Business')
@section('subheading', 'Launch your store with a clean workspace for products, billing, and sales history.')

@section('content')
    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="business_name" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Business Name</label>
            <input
                id="business_name"
                name="business_name"
                type="text"
                value="{{ old('business_name') }}"
                required
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-base text-ivory outline-none placeholder:text-white/30 focus:border-gold/60"
                placeholder="Colombo Mart"
            >
        </div>

        <div>
            <label for="name" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Owner Name</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                required
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-base text-ivory outline-none placeholder:text-white/30 focus:border-gold/60"
                placeholder="Nimal Perera"
            >
        </div>

        <div>
            <label for="email" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-base text-ivory outline-none placeholder:text-white/30 focus:border-gold/60"
                placeholder="owner@business.com"
            >
        </div>

        <div>
            <label for="password" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-base text-ivory outline-none placeholder:text-white/30 focus:border-gold/60"
                placeholder="Create password"
            >
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Confirm Password</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-base text-ivory outline-none placeholder:text-white/30 focus:border-gold/60"
                placeholder="Confirm password"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-gold px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-black transition hover:bg-[#e6c766]"
        >
            Create Workspace
        </button>

        <p class="text-center text-sm text-white/50">
            Already registered?
            <a href="{{ route('login') }}" class="text-gold transition hover:text-[#e6c766]">Sign in</a>
        </p>
    </form>
@endsection
