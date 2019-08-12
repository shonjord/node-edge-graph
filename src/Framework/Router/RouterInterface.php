<?php declare(strict_types=1);

namespace App\Framework\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    // dispatch a given request to return a new response
    public function dispatch(ServerRequestInterface $request): ResponseInterface;
}
