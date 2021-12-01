<?php

namespace AmphiBee\WooCommerceExtend\Providers;

use Themosis\Core\Support\Providers\RouteServiceProvider as ServiceProvider;
use Themosis\Support\Facades\Filter;

class ProductTypeProvider extends ServiceProvider
{
    public function register()
    {
        Filter::add( 'product_type_options', [$this, 'disableProductTypes']);
        Filter::add('product_type_selector', [$this, 'disableProductTypes']);
        Filter::add('woocommerce_account_menu_items', [$this, 'maybeDisableDownloadMenuItem']);
    }

    public function disableProductTypes( $types ) {
        $product_types = config('app.woocommerce.product_types', []);
        foreach ($product_types as $product_type=>$product_options) {
            if ( isset( $types[$product_type] ) && isset($product_options['disabled']) && $product_options['disabled'] === true ) {
                unset( $types[$product_type] );
            }
        }
        return $types;
    }

    public function maybeDisableDownloadMenuItem($items) {
        if ( isset( $items['downloads'] ) && config('app.woocommerce.product_types.downloadable.disabled', false) === true ) {
            unset($items['downloads']);
        }
        return $items;
    }
}
