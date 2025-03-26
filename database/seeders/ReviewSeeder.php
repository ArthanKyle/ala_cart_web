<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use Lunar\Models\Product;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::count() === 0) {
            Product::factory(5)->create(); 
        }

        
        $userIds = User::limit(10)->pluck('id')->toArray();
        $productIds = Product::limit(5)->pluck('id')->toArray();

        if (empty($productIds) || empty($userIds)) {
            return; 
        }

     
        foreach (range(1, 10) as $_) {
            $product = Product::find($productIds[array_rand($productIds)]);
            $user = User::find($userIds[array_rand($userIds)]);

  
            $review = Review::factory()->create([
                'product_id' => $product->id,
                'user_id' => $user->id,
            ]);

            $imageFile = $product->image ?? 'default.jpg'; 
            $path = base_path("database\seeders\data\images");
            
            if (file_exists($path)) {
                $review->addMedia($path)->preservingOriginal()->toMediaCollection('images');
            }
            
        }
    }
}
