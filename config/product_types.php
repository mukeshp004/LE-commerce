<?php

return [
    'simple'       => [
        'key'   => 'simple',
        'name'  => 'Simple',
        'class' => 'App\Product\Type\Simple',
        'sort'  => 1,
    ],

    'configurable' => [
        'key'   => 'configurable',
        'name'  => 'Configurable',
        'class' => 'App\Product\Type\Configurable',
        'sort'  => 2,
    ],

    'virtual'      => [
        'key'   => 'virtual',
        'name'  => 'Virtual',
        'class' => 'App\Product\Type\Virtual',
        'sort'  => 3,
    ],

    'grouped'      => [
        'key'   => 'grouped',
        'name'  => 'Grouped',
        'class' => 'App\Product\Type\Grouped',
        'sort'  => 4,
    ],

    'downloadable' => [
        'key'   => 'downloadable',
        'name'  => 'Downloadable',
        'class' => 'App\Product\Type\Downloadable',
        'sort'  => 5,
    ],

    'bundle'       => [
        'key'  => 'bundle',
        'name'  => 'Bundle',
        'class' => 'App\Product\Type\Bundle',
        'sort'  => 6,
    ]
];
