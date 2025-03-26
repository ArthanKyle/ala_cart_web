<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Lunar\Models\Product;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
    
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $imageFile = $product->image ?? 'default.jpg';
        $imagePath = base_path("database/seeders/data/images/{$imageFile}");

      
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'review' => $this->faker->realText(100),
        ]);

        if (file_exists($imagePath)) {
            $review->addMedia($imagePath)
                ->preservingOriginal()
                ->toMediaCollection('images');
        }

        return $review->toArray();
    }
}
