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

/**
 * Get current site name
 * @return string
 */
function site(): string
{
    if (!empty($_GET['site']))
        return $_GET['site'];
    else
        return sites()[0];
}