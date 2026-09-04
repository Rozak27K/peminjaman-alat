<?php

$tmpStoragePath = '/tmp/storage';

function runtimeEnv(string $key): ?string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    return is_string($value) && $value !== '' ? $value : null;
}

function setRuntimeEnv(string $key, string $value): void
{
    putenv($key.'='.$value);
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

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

setRuntimeEnv('LARAVEL_STORAGE_PATH', $tmpStoragePath);
setRuntimeEnv('LOG_CHANNEL', 'stderr');

$caCertificate = runtimeEnv('MYSQL_ATTR_SSL_CA_CONTENT');
$configuredCaCertificate = runtimeEnv('MYSQL_ATTR_SSL_CA');

if ($caCertificate === null && $configuredCaCertificate !== null && str_contains($configuredCaCertificate, 'BEGIN CERTIFICATE')) {
    $caCertificate = $configuredCaCertificate;
}

if ($caCertificate !== null) {
    $caCertificatePath = $tmpStoragePath.'/aiven-ca.pem';

    file_put_contents(
        $caCertificatePath,
        trim(str_replace('\n', "\n", $caCertificate)).PHP_EOL,
    );

    setRuntimeEnv('MYSQL_ATTR_SSL_CA', $caCertificatePath);
}

require __DIR__.'/../public/index.php';
