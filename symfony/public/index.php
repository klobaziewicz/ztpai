<?php

if (!defined('PhpAmqpLib\Wire\IO\SOCKET_EAGAIN')) {
    define('PhpAmqpLib\Wire\IO\SOCKET_EAGAIN', 10035);
}

if (!defined('PhpAmqpLib\Wire\IO\SOCKET_EWOULDBLOCK')) {
    define('PhpAmqpLib\Wire\IO\SOCKET_EWOULDBLOCK', 10035);
}
if (!defined('PhpAmqpLib\Wire\IO\SOCKET_EINTR')) {
    define('PhpAmqpLib\Wire\IO\SOCKET_EINTR', 10004);
}

if (php_sapi_name() === 'cli-server') {
    $path = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($path)) {
        return false;
    }
}
ob_start();

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

register_shutdown_function(function () {
    ob_end_flush();
});