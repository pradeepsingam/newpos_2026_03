@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Products</h1>
                <p class="text-sm text-slate-500">Manage products for your business.</p>
            </div>

            <a
                href="{{ route('products.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white"
            >
                Add Product
            </a>
        </div>

        @if ($products->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center">
                <p class="text-base font-medium text-slate-800">No products yet.</p>
                <p class="mt-2 text-sm text-slate-500">Create your first product to start selling.</p>
            </div>
        @else
            <div class="grid gap-3">
                @foreach ($products as $product)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="space-y-2">
                                <h2 class="text-lg font-semibold text-slate-900">{{ $product->name }}</h2>
                                <div class="flex flex-wrap gap-2 text-sm text-slate-600">
                                    <span class="rounded-full bg-slate-100 px-3 py-1">Price: {{ number_format((float) $product->price, 2) }}</span>
                                    <span class="rounded-full bg-slate-100 px-3 py-1">Stock: {{ $product->stock }}</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a
                                    href="{{ route('products.edit', $product) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700"
                                >
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('products.destroy', $product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600"
                                        onclick="return confirm('Delete this product?')"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
