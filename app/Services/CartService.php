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
        $currency = Currency::first();
        $channel = Channel::first();

        $customer = Customer::where('user_id', $userId)->first();

        if (! $customer) {
            return response()->json(['error' => 'Customer not found for this user'], 404);
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            [
                'customer_id' => $customer->id,
                'currency_id' => $currency->id,
                'channel_id' => $channel->id,
            ]
        );

        $productVariant = ProductVariant::find($productId);

        if (! $productVariant) {
            return response()->json(['error' => 'Product variant not found'], 404);
        }

        if ($productVariant->stock < $quantity) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        $cartLine = CartLine::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'purchasable_id' => $productId,
                'purchasable_type' => 'Lunar\Models\ProductVariant',
            ],
            ['quantity' => $quantity]
        );

        $productVariant->decrement('stock', $quantity);

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_line' => $cartLine,
            'remaining_stock' => $productVariant->stock,
        ]);
    }

    public function updateCartItem($userId, $cartLineId, $newQuantity)
    {
        $cartLine = CartLine::whereHas('cart', fn ($query) => $query->where('user_id', $userId))
            ->findOrFail($cartLineId);

        $productVariant = ProductVariant::find($cartLine->purchasable_id);

        if (! $productVariant) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $stockDifference = $newQuantity - $cartLine->quantity;

        // Check if stock is available
        if ($stockDifference > 0 && $productVariant->stock < $stockDifference) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        // Adjust stock based on quantity change
        if ($stockDifference > 0) {
            $productVariant->decrement('stock', $stockDifference);
        } else {
            $productVariant->increment('stock', abs($stockDifference));
        }

        // Update cart item quantity
        $cartLine->update(['quantity' => $newQuantity]);

        return ['success' => true, 'new_stock' => $productVariant->stock];
    }

    public function deleteCartItem($userId, $cartLineId)
    {
        $cartLine = CartLine::whereHas('cart', fn ($query) => $query->where('user_id', $userId))
            ->findOrFail($cartLineId);

        $product = Product::find($cartLine->product_id);

        if (! $product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Restore stock when item is removed
        $product->increment('stock', $cartLine->quantity);

        // Delete cart item
        $cartLine->delete();

        return response()->json(['success' => true, 'new_stock' => $product->stock]);
    }
}
