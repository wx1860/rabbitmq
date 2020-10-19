<?php

// +----------------------------------------------------------------------
// | Rabbitmq设置
// +----------------------------------------------------------------------


return [

    'default' => env('RABBITMQ_DRIVER', 'local'),

    'connections' => [
        'local' => [
            'driver'    => 'rabbitmq',
            'host'      => env('RABBITMQ_HOST','127.0.0.1'),
            'port'      => env('RABBITMQ_PORT','5672'),
            'username'  => env('RABBITMQ_USER','guest'),
            'password'  => env('RABBITMQ_PASS','guest'),
            'vhost'  => env('RABBITMQ_VHOST','test'),
        ],
        'kdy' => [
            'driver'    => 'rabbitmq',
            'host'      => env('RABBITMQ_HOST','39.100.51.18'),
            'port'      => env('RABBITMQ_PORT','5672'),
            'username'  => env('RABBITMQ_USER','kdy-test'),
            'password'  => env('RABBITMQ_PASS','y3s7S3cWfdjOjJXy%&'),
            'vhost'  => env('RABBITMQ_VHOST','test'),
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
