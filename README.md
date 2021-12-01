WooCommerce Extend
==================

The plugin is configured to work on a Themosis framework like environment only.

It extends WooCommerce functionalities.

You can manager custom order statuses within the back-office and enable / disable product types from the configuration file of your application.

To enable or disable product type, go to your config/app.php file and set the following value:

<pre>
return [
    [...]
    'woocommerce' => [
        'product_types' => [
            'virtual' => [
                'disabled' => true,
            ],
            'downloadable' => [
                'disabled' => true,
            ],
            'external' => [
                'disabled' => true,
            ],
        ],
    ],
];
</pre>

[Read the documentation](https://framework.themosis.com/docs/2.0/plugin/) on the Themosis framework website and start developing your own plugin.
