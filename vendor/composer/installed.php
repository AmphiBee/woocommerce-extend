<?php return array(
    'root' => array(
        'name' => 'plugin/name',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'cf1d11e7e0ed55ed23318684a1ada34327a546f7',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'htmlburger/carbon-fields' => array(
            'pretty_version' => 'v3.3.2',
            'version' => '3.3.2.0',
            'reference' => 'dd5663e14c6db365323b688dbae1cfbeaf14bee7',
            'type' => 'library',
            'install_path' => __DIR__ . '/../htmlburger/carbon-fields',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'plugin/name' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'cf1d11e7e0ed55ed23318684a1ada34327a546f7',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'roundcube/plugin-installer' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
    ),
);
