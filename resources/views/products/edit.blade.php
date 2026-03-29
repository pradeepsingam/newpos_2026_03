@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-900">Edit Product</h1>
            <p class="mt-1 text-sm text-slate-500">Update product details for your business.</p>
        </div>

        <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $product->name) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none placeholder:text-slate-300 focus:border-slate-500"
                    placeholder="Milk packet"
                >
            </div>

            <div>
                <label for="price" class="mb-2 block text-sm font-medium text-slate-700">Price</label>
                <input
                    id="price"
                    name="price"
                    type="number"
                    step="0.01"
                    min="0"
                    value="{{ old('price', $product->price) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none placeholder:text-slate-300 focus:border-slate-500"
                    placeholder="150.00"
                >
            </div>

            <div>
                <label for="stock" class="mb-2 block text-sm font-medium text-slate-700">Stock</label>
                <input
                    id="stock"
                    name="stock"
                    type="number"
                    min="0"
                    value="{{ old('stock', $product->stock) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none placeholder:text-slate-300 focus:border-slate-500"
                    placeholder="0"
                >
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white"
                >
                    Update Product
                </button>

                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
