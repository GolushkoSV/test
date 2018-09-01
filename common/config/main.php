<?php
$db = require __DIR__ . '/db.php';

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru-Ru',
    'components' => [
        'db'=>$db,
            'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
