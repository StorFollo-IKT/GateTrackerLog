<?php
function config($site): array
{
    $config = require __DIR__ . '/config.php';
    if (empty($site))
        return array_values($config)[0];
    elseif (isset($config[$site]))
        return $config[$site];
    else
        throw new RuntimeException('Invalid site');
}

function sites(): array
{
    $config = require __DIR__ . '/config.php';
    return array_keys($config);
}