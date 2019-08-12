<?php declare(strict_types=1);

use App\Framework\Router\RouterInterface;
use App\Presentation\Http\Request\Request;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

// Emitting the returned response by the router
(function (RouterInterface $router) : void {
    (new SapiEmitter())->emit(
        $router->dispatch(Request::fromGlobals())
    );
})(require_once __DIR__ . '/../router/router.php');
