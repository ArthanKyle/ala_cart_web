<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = $this->productService->getAllProducts();

        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show(string $id)
    {
        $product = $this->productService->getProductById($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product,
            'total_reviews' => $product->total_reviews,
            'average_rating' => $product->average_rating,
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(ProductRequest $request, string $id)
    {
        $product = $this->productService->updateProduct($id, $request->validated());
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->productService->deleteProduct($id);
        if (! $deleted) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
