<?php

$tmpStoragePath = '/tmp/storage';

foreach ([
    $tmpStoragePath.'/framework/cache/data',
    $tmpStoragePath.'/framework/sessions',
    $tmpStoragePath.'/framework/views',
    $tmpStoragePath.'/logs',
] as $path) {
    if (! is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

putenv('LARAVEL_STORAGE_PATH='.$tmpStoragePath);
$_ENV['LARAVEL_STORAGE_PATH'] = $tmpStoragePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $tmpStoragePath;

putenv('LOG_CHANNEL=stderr');
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

require __DIR__.'/../public/index.php';
