<?php declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class LazyMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    private $middleware;

    public function __construct(callable $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return ($this->middleware)($request,$handler);
    }
}
