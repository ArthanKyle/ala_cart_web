<?php

namespace App\Services;

use Lunar\Models\Product;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with(['images', 'prices', 'variants.values', 'reviews'])
            ->paginate()
            ->through(function ($product) {
                foreach ($product->images as $image) {
                    $image->url = url($image->path);
                }
    
                $product->price = $product->prices->first()?->price->value ?? null;
                $product->stock = $product->prices->first()?->priceable?->stock ?? 0;

                $product->average_rating = round($product->reviews->avg('rating') ?? 0, 1);
    
                // Attach variants with option values
                $product->variants = $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'stock' => $variant->stock,
                        'price' => $variant->prices->first()?->price->value ?? null,
                        'options' => $variant->values->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'name' => json_decode($option->name)->{'en'} ?? $option->name, // Decoding JSON name if necessary
                            ];
                        }),
                    ];
                });
    
                return $product;
            });
    }    
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function getProductById($id)
    {
        return Product::with('reviews')->find($id)?->append('average_rating');
    }

    public function updateProduct($id, array $data)
    {
        $product = Product::find($id);
        if (! $product) {
            return null;
        }

        $product->update($data);

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return false;
        }

        $product->delete();

        return true;
    }
}
