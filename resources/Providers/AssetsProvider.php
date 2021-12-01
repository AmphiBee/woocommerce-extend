<?php

namespace AmphiBee\WooCommerceExtend\Providers;

use Themosis\Support\Facades\Action;
use Themosis\Core\Support\Providers\RouteServiceProvider as ServiceProvider;
use Themosis\Support\Facades\Asset;

class AssetsProvider extends ServiceProvider
{
    protected $plugin;

    public function register()
    {

        $this->plugin = $this->app->make('wp.plugin.woocommerce-extend');
        Action::add('init', [$this, 'loadScripts']);
    }

    public function loadScripts() {
        $scripts = Asset::add('order_status_styles', 'index.js', ['jquery'], '1.0', true)->to('admin');
        Asset::add('order_status_styles', 'style.css', [], '1.0', 'all')->to('admin');
        $this->app->singleton('woocommerce-extend.order_statuses.scripts', function ($app) use ($scripts) {
            return $scripts;
        });
    }
}
