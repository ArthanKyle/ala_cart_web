<?php

namespace App\Services;

use Lunar\Models\Product;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with('images')->paginate();
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function getProductById($id)
    {
        return Product::find($id);
    }

    public function updateProduct($id, array $data)
    {
        $product = Product::find($id);
        if (!$product) {
            return null;
        }

        $product->update($data);
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return false;
        }

        $product->delete();
        return true;
    }
}
