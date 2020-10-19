<?php

// +----------------------------------------------------------------------
// | Rabbitmq设置
// +----------------------------------------------------------------------


return [

    'default' => env('RABBITMQ_DRIVER', 'local'),

    'connections' => [
        'local' => [
            'driver'    => 'rabbitmq',
            'host'      => env('RABBITMQ.LOCAL_HOST',''),
            'port'      => env('RABBITMQ.LOCAL_PORT',''),
            'username'  => env('RABBITMQ.LOCAL_USER',''),
            'password'  => env('RABBITMQ.LOCAL_PASSWORD',''),
            'vhost'  => env('RABBITMQ.LOCAL_VHOST',''),
        ],
        'kdy' => [
            'driver'    => 'rabbitmq',
            'host'      => env('RABBITMQ.KDY_HOST',''),
            'port'      => env('RABBITMQ.KDY_PORT',''),
            'username'  => env('RABBITMQ.KDY_USER',''),
            'password'  => env('RABBITMQ.KDY_PASSWORD',''),
            'vhost'  => env('RABBITMQ.KDY_VHOST',''),
        ],
    ],

    'exchange' => [
          'shop' => [
              'name'        => 'shop_exchange',
              'type'        => 'direct',
              'passive'     => false,
              'durable'     => false,
              'auto_delete' => false,
          ],
     ],

    'queue' => [
        'update_product' => [
            'exchange'    => 'shop',
            'route_key'   => 'update_product',
            'name'        => 'update_product_queue',
            'type'        => false,
            'passive'     => false,
            'durable'     => true,
            'auto_delete' => false,
        ],
    ],

];
