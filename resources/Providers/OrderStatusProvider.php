<?php

namespace AmphiBee\WooCommerceExtend\Providers;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Themosis\Core\Support\Providers\RouteServiceProvider as ServiceProvider;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\PostType;

class OrderStatusProvider extends ServiceProvider
{
    const ORDER_STATUS_POST_TYPE = 'order_statuses';

    public function boot()
    {
        Action::add('init', [$this, 'registerPostType']);
        Action::add('init', [$this, 'registerCustomOrderStatuses']);
        Action::add( 'wc_order_statuses', [$this, 'addCustomOrderStatuses']);
        Action::add( 'load-edit.php', [$this, 'registerCustomStatusBulkAction'],11);
        Action::add('init', [$this, 'registerJsVars']);
        Action::add( 'admin_head', array( $this, 'printStyles'),11);
    }

    public function printStyles() {
        $query = $this->getCustomOrderStatuses();

        if( $query->have_posts() ) {

            $styles = '<style>';
            while ($query->have_posts()) {
                $query->the_post();

                $statusColor = get_post_meta(get_the_ID(), '_status_color', true);
                $post = get_post();
                if ($statusColor) {
                    $bgStatusColor = $this->adjustBrightness($statusColor, 0.8);
                    $styles .= '.status-' . $post->post_name . ' {
                        color: ' . $statusColor . ';
                        background-color: ' . $bgStatusColor . ';
                    }';
                }
            }
            $styles .= '</style>';

            echo $styles;
        }
    }

    public function registerCustomStatusBulkAction() {
        $wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
        $report_action = $wp_list_table->current_action(); //wc-shipped

        $current_screen = get_current_screen();


        if (!$current_screen || $current_screen->id !== 'edit-shop_order' || !$report_action) {
            return;
        }

        $statuses = array_keys(wc_get_order_statuses());
        $new_status = substr($report_action, 5, strlen($report_action));

        if (in_array($new_status, $statuses)) {
            return;
        }

        $post_ids = array_map( 'absint', (array) $_REQUEST['post'] );

        $this->bulkUpdateStatuses($post_ids, $report_action, $new_status);

    }

    protected function bulkUpdateStatuses($post_ids, $report_action, $new_status) {
        $statuses = array_keys(wc_get_order_statuses());
        if (!in_array($new_status, $statuses)) {
            return;
        }

        foreach ( $post_ids as $post_id ) {
            $order = wc_get_order( $post_id );
            $order->update_status( $new_status, __( 'Order status changed by bulk edit:', 'woocommerce' ) );
            $changed++;
        }

        $sendback = add_query_arg( array( 'post_type' => 'shop_order', $report_action => true, 'changed' => $changed, 'ids' => join( ',', $post_ids ) ), '' );
        wp_redirect( $sendback );
        exit();
    }

    public function registerJsVars() {
        $query = $this->getCustomOrderStatuses();

        $jsVars = [];
        while ($query->have_posts()) {
            $query->the_post();
            $post = get_post();
            $bulkActionText = get_post_meta( get_the_ID(), '_status_bulk_text', true );
            $statusIcon = get_post_meta( get_the_ID(), '_status_icon', true );
            $statusIconHtml = $statusIcon ? wp_get_attachment_image( (int)$statusIcon, 'full', false, ['height' => 30, 'width' => 20 ] ) : false;
            $jsVars[$post->post_name] = [
                'bulk_action' => $bulkActionText,
                'icon' => $statusIconHtml,
            ];
        }
        $scripts = $this->app->make('woocommerce-extend.order_statuses.scripts');
        $scripts->localize('WCE_Order_Statuses', $jsVars);
    }

    public function register()
    {
        Action::add('carbon_fields_register_fields', [$this, 'registerMetabox']);
    }

    public function getCustomOrderStatuses() {
        return new \WP_Query([
            'post_type' => self::ORDER_STATUS_POST_TYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
    }

    public function addCustomOrderStatuses($order_statuses) {
        $query = $this->getCustomOrderStatuses();

        while ($query->have_posts()) {
            $query->the_post();
            $post = get_post();

            $nextStep = get_post_meta( get_the_ID(), '_status_next_step', true );
            $searchKey = array_search($nextStep, array_keys($order_statuses));
            $res = array_slice($order_statuses, 0, $searchKey, true) +
                ['wc-' . $post->post_name => $post->post_title] +
                array_slice($order_statuses, $searchKey, count($order_statuses) - 1, true);

            $order_statuses = $res;
        }

        return $order_statuses;
    }

    public function registerCustomOrderStatuses() {
        $query = $this->getCustomOrderStatuses();

        while ($query->have_posts()) {

            $query->the_post();
            $post = get_post();

            register_post_status( 'wc-' . $post->post_name, [
                'label'                     => _x('wc-' . $post->post_title, WCE_TD),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop("{$post->post_title} <span class=\"count\">(%s)</span>", "{$post->post_title} <span class=\"count\">(%s)</span>"),
            ]);
        }
    }

    protected function adjustBrightness($hexCode, $adjustPercent) {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as & $color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }

    /**
     * Register the post type.
     */
    public function registerPostType() {
        PostType::make(self::ORDER_STATUS_POST_TYPE, __('Order statuses', WCE_TD), __('Order status', WCE_TD))
            ->setLabels([
                'all_items' => _x('Order statuses', 'post_type', WCE_TD)
            ])
            ->setArguments([
                'public' => false,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'show_ui' => true,
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'show_in_rest' => true,
                'supports' => ['title'],
                'menu_icon' => 'dashicons-cart',
                'rewrite' => false,
                'has_archive' => false,
                'hierarchical' => false,
                'query_var' => false,
                'can_export' => false,
                'delete_with_user' => false,
                'rest_base' => 'order_statuses',
                'show_in_menu' => 'woocommerce'
            ])
            ->set();
    }

    public function getOrderStatuses() {
        $orderStatuses = wc_get_order_statuses();

        if (!is_admin()) {
            return $orderStatuses;
        }

        $currentScreen = get_current_screen();
        if ($currentScreen->id === 'order_statuses' && isset($_GET['post'])) {
            $orderStatus = get_post($_GET['post']);
            if (isset($orderStatuses['wc-' . $orderStatus->post_name])) {
                unset($orderStatuses['wc-' . $orderStatus->post_name]);
            }
        }

        return $orderStatuses;
    }

    /**
     * Register the metabox.
     * If the fields are not shown : php artisan vendor:publish --tag=themosis --force
     */
    public function registerMetabox() {
        Container::make('post_meta', _x('Order status settings', 'metabox', WCE_TD))
            ->where('post_type', '=', self::ORDER_STATUS_POST_TYPE)
            ->add_fields([
                Field::make('textarea', 'status_description', __('Description', WCE_TD))
                    ->set_help_text(__('The description of the order status', WCE_TD)),
                Field::make('text', 'status_bulk_text', __('Bulk action text', WCE_TD)),
                Field::make('color', 'status_color', __('Color', WCE_TD)),
                Field::make('image', 'status_icon', __('Icon', WCE_TD))
                    ->set_help_text(__('The icon of the order status', WCE_TD))
                    ->set_width(50),
                Field::make('select', 'status_next_step', __('Next step', WCE_TD))
                    ->add_options([$this, 'getOrderStatuses'])
                    ->set_help_text(__('The next step of the order status', WCE_TD))

            ]);
    }
}
