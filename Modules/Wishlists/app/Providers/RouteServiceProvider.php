<?php

namespace Modules\Wishlists\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Wishlists\Models\WishlistItem;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = "Wishlists";

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        Route::bind("myWishedItem", function ($value) {
            return WishlistItem::where("id", $value)
                ->where("wishlist_id", wishlistService()?->wishlist->id)
                ->firstOrFail();
        });

        Route::bind("myWishedProduct", function ($value) {
            return WishlistItem::where("product_id", $value)
                ->where("wishlist_id", wishlistService()?->wishlist->id)
                ->firstOrFail();
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        // $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware("web")->group(
            module_path($this->name, "/routes/web.php")
        );
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware("api")
            ->prefix("api")
            ->name("api.")
            ->group(module_path($this->name, "/routes/api.php"));
    }
}
