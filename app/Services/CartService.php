<?php

namespace App\Services;

use App\Models\Product;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Lunar\Models\Channel;
use Lunar\Models\Currency;
use Lunar\Models\Customer;
use Lunar\Models\ProductVariant;

class CartService
{
    public function getUserCart($userId)
    {
        $cart = Cart::where('user_id', $userId)
            ->with('lines.purchasable.prices') // Load purchasable and its prices
            ->first();

        if (! $cart) {
            return ['message' => 'Cart not found'];
        }

        return [
            'cart_id' => $cart->id,
            'items' => $cart->lines->map(function ($line) {
                $variant = $line->purchasable; // Use 'purchasable' for polymorphic relation

                if (! $variant) {
                    return [
                        'id' => $line->id,
                        'quantity' => $line->quantity,
                        'total' => 0,
                        'purchasable' => null, // Handle missing variant gracefully
                    ];
                }

                return [
                    'id' => $line->id,
                    'quantity' => $line->quantity,
                    'total' => $line->price ?? (($variant->prices->first()->price->value ?? 0) * $line->quantity),
                    'purchasable' => [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'price' => optional($variant->prices->first())->price ?? null,
                        'stock' => $variant->stock,
                        'product_name' => optional($variant->product)->translateAttribute('name') ?? 'Unknown Product',
                        'image' => optional($variant->getThumbnail())->getUrl(),
                    ],
                ];
            }),
        ];
    }

    public function addItemToCart($userId, $productId, $quantity)
    {
        $currency = Currency::first(); // Get default currency
        $channel = Channel::first(); // Get default channel

        // Find the customer linked to the user
        $customer = Customer::where('user_id', $userId)->first();

        if (! $customer) {
            return response()->json(['error' => 'Customer not found for this user'], 404);
        }

        // Ensure the user has a cart
        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            [
                'customer_id' => $customer->id,
                'currency_id' => $currency->id,
                'channel_id' => $channel->id,
            ]
        );

        // Find the product variant
        $productVariant = ProductVariant::find($productId);

        if (! $productVariant) {
            return response()->json(['error' => 'Product variant not found'], 404);
        }

        // Check if there is enough stock
        if ($productVariant->stock < $quantity) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        // Add item to cart
        $cartLine = CartLine::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'purchasable_id' => $productId,
                'purchasable_type' => 'Lunar\Models\ProductVariant', // Adjust if necessary
            ],
            ['quantity' => $quantity]
        );

        // Reduce stock after adding to cart
        $productVariant->decrement('stock', $quantity);

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_line' => $cartLine,
            'remaining_stock' => $productVariant->stock,
        ]);
    }

    public function updateCartItem($userId, $cartLineId, $quantity)
    {
        $cartLine = CartLine::whereHas('cart', fn ($query) => $query->where('user_id', $userId))
            ->findOrFail($cartLineId);

        $cartLine->update(['quantity' => $quantity]);

        return $cartLine;
    }

    public function deleteCartItem($userId, $cartLineId)
    {
        $cartLine = CartLine::whereHas('cart', fn ($query) => $query->where('user_id', $userId))
            ->findOrFail($cartLineId);

        $productId = $cartLine->product_id;
        $product = Product::find($productId);

        if (! $product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $stockQuantity = $product->stock;
        $cartLine->delete();

        return response()->json(['stock' => $stockQuantity], 200);
    }
}
