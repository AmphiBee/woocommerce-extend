<?php return array(
    'root' => array(
        'name' => 'plugin/name',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => '92d872bc3960bad80f28fe1ce5d211dfeedfebf8',
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
            'pretty_version' => 'v3.3.4',
            'version' => '3.3.4.0',
            'reference' => '2ae6773c004b873a1b0456613b14852c1a436a96',
            'type' => 'library',
            'install_path' => __DIR__ . '/../htmlburger/carbon-fields',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'plugin/name' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '92d872bc3960bad80f28fe1ce5d211dfeedfebf8',
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
