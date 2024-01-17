<?php return array(
    'root' => array(
        'name' => 'wpforms/stripe',
        'pretty_version' => 'dev-develop',
        'version' => 'dev-develop',
        'reference' => 'b3d56ed9696f30da0c9f33fbdcfb430b2de8afa9',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'roave/security-advisories' => array(
            'pretty_version' => 'dev-latest',
            'version' => 'dev-latest',
            'reference' => '7a0792500b5d9f0b4d81fba229b191e747bd2ef2',
            'type' => 'metapackage',
            'install_path' => NULL,
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => true,
        ),
        'wpforms/stripe' => array(
            'pretty_version' => 'dev-develop',
            'version' => 'dev-develop',
            'reference' => 'b3d56ed9696f30da0c9f33fbdcfb430b2de8afa9',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
