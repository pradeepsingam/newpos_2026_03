<?php

namespace App\Examples;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Support\Tenant;

class TenantQueryExamples
{
    public function examples(): array
    {
        return [
            'implicit_scope' => Product::query()->latest()->get(),
            'explicit_scope' => Product::query()
                ->forBusiness(Tenant::id())
                ->where('stock', '>', 0)
                ->get(),
            'sales' => Sale::query()
                ->forBusiness(Tenant::id())
                ->with('items.product')
                ->latest()
                ->get(),
            'users' => User::query()
                ->forBusiness(Tenant::id())
                ->orderBy('name')
                ->get(),
        ];
    }
}
