<?php declare(strict_types=1);

use App\Framework\Router\Symfony\Router;
use Psr\Container\ContainerInterface;

return (function (ContainerInterface $container) : Router {
    return new Router(
        $container,
        __DIR__ . '/../config/routing/routes.yml',
        __DIR__ . '/../cache'
    );
})(require_once __DIR__ . '/../container/container.php');
