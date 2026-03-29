<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $business = Business::query()->findOrFail((int) auth()->user()->business_id);

        $products = Product::query()
            ->orderBy('name')
            ->get();

        return view('pos.index', [
            'business' => $business,
            'products' => $products,
        ]);
    }

    public function lookupCustomer(Request $request): JsonResponse
    {
        $query = trim((string) $request->string('query')->toString());

        if ($query === '') {
            return response()->json([
                'customers' => [],
            ]);
        }

        $customers = Customer::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('phone', $query)
                    ->orWhere('name', 'like', '%' . $query . '%');
            })
            ->orderByRaw('case when phone = ? then 0 when lower(name) = lower(?) then 1 else 2 end', [$query, $query])
            ->orderBy('name')
            ->limit(5)
            ->get();

        return response()->json([
            'customers' => $customers->map(fn (Customer $customer) => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'points_balance' => $customer->points_balance,
            ])->values(),
        ]);
    }

    public function checkout(Request $request): JsonResponse|RedirectResponse
    {
        $business = Business::query()->findOrFail((int) auth()->user()->business_id);

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,card'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'is_walking_customer' => ['required', 'boolean'],
            'redeemed_points' => ['nullable', 'integer', 'min:0'],
        ]);

        $sale = DB::transaction(function () use ($validated, $business) {
            $items = collect($validated['items']);
            $products = Product::query()
                ->whereIn('id', $items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== $items->count()) {
                throw ValidationException::withMessages([
                    'items' => 'One or more selected products are not available for this business.',
                ]);
            }

            $totalAmount = 0;

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for {$product->name}.",
                    ]);
                }

                $linePrice = (float) $product->price;
                $totalAmount += $linePrice * $quantity;

                $product->decrement('stock', $quantity);
            }

            $walkingCustomer = (bool) $validated['is_walking_customer'];
            $customerName = $walkingCustomer
                ? 'Walking Customer'
                : trim((string) ($validated['customer_name'] ?? ''));
            $customerPhone = $walkingCustomer
                ? null
                : trim((string) ($validated['customer_phone'] ?? ''));

            if (! $walkingCustomer && $customerName === '') {
                throw ValidationException::withMessages([
                    'customer_name' => 'Customer name is required unless this is a walking customer.',
                ]);
            }

            $customer = null;
            $redeemedPoints = (int) ($validated['redeemed_points'] ?? 0);

            if (! $walkingCustomer) {
                $customer = Customer::query()->firstOrCreate(
                    [
                        'name' => $customerName,
                        'phone' => $customerPhone !== '' ? $customerPhone : null,
                    ],
                    [
                        'name' => $customerName,
                        'phone' => $customerPhone !== '' ? $customerPhone : null,
                        'points_balance' => 0,
                    ]
                );

                if ($customerPhone !== '' && $customer->phone !== $customerPhone) {
                    $customer->update(['phone' => $customerPhone]);
                }
            }

            if ($walkingCustomer) {
                $redeemedPoints = 0;
            }

            if ($customer && $redeemedPoints > $customer->points_balance) {
                throw ValidationException::withMessages([
                    'redeemed_points' => 'Redeemed points cannot exceed the customer points balance.',
                ]);
            }

            if ($redeemedPoints > $totalAmount) {
                throw ValidationException::withMessages([
                    'redeemed_points' => 'Redeemed points cannot exceed the bill total.',
                ]);
            }

            $finalTotal = max(0, $totalAmount - $redeemedPoints);
            $amountPaid = (float) $validated['amount_paid'];

            if ($amountPaid < $finalTotal) {
                throw ValidationException::withMessages([
                    'amount_paid' => 'Amount paid must be equal to or greater than the final total.',
                ]);
            }

            $earnedPoints = $customer
                ? (int) floor(($finalTotal * (float) $business->points_percentage) / 100)
                : 0;

            if ($customer) {
                $customer->forceFill([
                    'points_balance' => max(0, $customer->points_balance - $redeemedPoints + $earnedPoints),
                ])->save();
            }

            $sale = Sale::create([
                'subtotal_amount' => $totalAmount,
                'total_amount' => $finalTotal,
                'customer_id' => $customer?->id,
                'redeemed_points' => $redeemedPoints,
                'earned_points' => $earnedPoints,
                'payment_method' => $validated['payment_method'],
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone !== '' ? $customerPhone : null,
                'is_walking_customer' => $walkingCustomer,
                'amount_paid' => $amountPaid,
                'balance_amount' => $amountPaid - $finalTotal,
            ]);

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                $quantity = (int) $item['quantity'];
                $linePrice = (float) $product->price;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $linePrice,
                ]);
            }

            return $sale->load('items.product');
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Sale completed successfully.',
                'sale' => $sale,
                'receipt_url' => route('sales.receipt', $sale),
                'customer' => $sale->customer ? [
                    'id' => $sale->customer->id,
                    'points_balance' => $sale->customer->points_balance,
                ] : null,
            ]);
        }

        return redirect()
            ->route('sales.receipt', $sale)
            ->with('status', 'Sale completed successfully.');
    }

    public function receipt(Sale $sale): View
    {
        $sale->load(['business', 'customer', 'items.product']);

        return view('pos.receipt', [
            'sale' => $sale,
        ]);
    }
}
