<?php
return [
    'redis' => [
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
    ],
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'blog',
        'user' => 'root',
        'pass' => '123456',
        'charset' => 'utf8',
    ],
    'email' => [
        'mode' => 'production',    // 值：debug  和 production
        'port' => 25,
        'host' => 'hei_time@126.com',
        'name' => 'hei_time@126.com',
        'pass' => 'asdfghjkl123',
        'from_email' => '457340@qq.com',
        'from_name' => '全栈1班',
    ]
];