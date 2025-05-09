<?php

namespace App\Providers;

use App\Modifiers\ShippingModifier;
use App\Services\ProfilePictureService;
use App\Services\ProfilePictureServiceInterface;
use App\Services\ReviewService;
use App\Services\ReviewServiceInterface;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Base\ShippingModifiers;
use Lunar\Shipping\ShippingPlugin;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(
            fn ($panel) => $panel->plugins([
                new ShippingPlugin,
            ])
        )
            ->register();

        $this->app->bind(ProfilePictureServiceInterface::class, ProfilePictureService::class);
        $this->app->bind(ReviewServiceInterface::class, ReviewService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ShippingModifiers $shippingModifiers): void
    {
        $shippingModifiers->add(
            ShippingModifier::class
        );

        \Lunar\Facades\ModelManifest::replace(
            \Lunar\Models\Contracts\Product::class,
            \App\Models\Product::class,
            // \App\Models\CustomProduct::class,
        );
        Relation::morphMap([
            'product_variant' => \App\Models\ProductVariant::class,
        ]);
    }
}
