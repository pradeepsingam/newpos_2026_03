@extends('layouts.auth')

@section('title', 'Login')
@section('heading', 'Welcome Back')
@section('subheading', 'Sign in to continue billing, manage products, and review sales inside your business workspace.')

@section('content')
    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-xs uppercase tracking-[0.18em] text-white/55">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
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
                placeholder="Enter your password"
            >
        </div>

        <label class="flex items-center gap-3 text-sm text-white/65">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-white/20 bg-black/20 text-gold focus:ring-gold/40">
            Keep me signed in
        </label>

        <button
            type="submit"
            class="w-full rounded-xl bg-gold px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-black transition hover:bg-[#e6c766]"
        >
            Sign In
        </button>

        <p class="text-center text-sm text-white/50">
            New business?
            <a href="{{ route('register') }}" class="text-gold transition hover:text-[#e6c766]">Create an account</a>
        </p>
    </form>
@endsection
