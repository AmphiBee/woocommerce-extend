<?php

namespace AmphiBee\WooCommerceExtend\Providers;

use Carbon_Fields\Carbon_Fields;
use Themosis\Core\Support\Providers\RouteServiceProvider as ServiceProvider;
use Themosis\Support\Facades\Action;

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Action::add('after_setup_theme', [$this, 'registerPostType']);
    }
    /**
     * Register the post type.
     */
    public function registerPostType() {
        Carbon_Fields::boot();
    }
}
