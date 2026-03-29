<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $businesses = [
            [
                'name' => 'Colombo Mart',
                'owner' => [
                    'name' => 'Nimal Perera',
                    'email' => 'owner@colombomart.test',
                    'password' => Hash::make('password'),
                ],
                'products' => [
                    ['name' => 'Ceylon Tea Pack', 'price' => 1250.00, 'stock' => 50],
                    ['name' => 'Short Eats Box', 'price' => 450.00, 'stock' => 30],
                ],
            ],
            [
                'name' => 'Kandy Stores',
                'owner' => [
                    'name' => 'Ayesha Silva',
                    'email' => 'owner@kandystores.test',
                    'password' => Hash::make('password'),
                ],
                'products' => [
                    ['name' => 'Rice Bag 5kg', 'price' => 1850.00, 'stock' => 40],
                    ['name' => 'Coconut Oil 1L', 'price' => 980.00, 'stock' => 25],
                ],
            ],
        ];

        foreach ($businesses as $entry) {
            $business = Business::create([
                'name' => $entry['name'],
                'points_percentage' => 5,
                'subscription_package' => 'Starter',
                'subscription_starts_at' => Carbon::today(),
                'subscription_ends_at' => Carbon::today()->addDays(30),
                'is_active' => true,
            ]);

            $owner = User::withoutGlobalScopes()->create([
                'business_id' => $business->id,
                'name' => $entry['owner']['name'],
                'email' => $entry['owner']['email'],
                'password' => $entry['owner']['password'],
            ]);

            $business->update([
                'owner_id' => $owner->id,
            ]);

            $products = collect($entry['products'])->map(function (array $product) use ($business) {
                return Product::withoutGlobalScopes()->create([
                    'business_id' => $business->id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                ]);
            });

            $sale = Sale::withoutGlobalScopes()->create([
                'business_id' => $business->id,
                'subtotal_amount' => 0,
                'total_amount' => 0,
                'redeemed_points' => 0,
                'earned_points' => 0,
                'payment_method' => 'cash',
                'customer_name' => 'Walking Customer',
                'customer_phone' => null,
                'is_walking_customer' => true,
                'amount_paid' => 0,
                'balance_amount' => 0,
            ]);

            $lineItems = [
                [
                    'product' => $products[0],
                    'quantity' => 2,
                    'price' => $products[0]->price,
                ],
                [
                    'product' => $products[1],
                    'quantity' => 1,
                    'price' => $products[1]->price,
                ],
            ];

            $total = 0;

            foreach ($lineItems as $item) {
                SaleItem::withoutGlobalScopes()->create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $total += $item['quantity'] * $item['price'];

                $item['product']->decrement('stock', $item['quantity']);
            }

            $sale->update([
                'subtotal_amount' => $total,
                'total_amount' => $total,
                'amount_paid' => $total,
                'balance_amount' => 0,
            ]);
        }
    }
}
