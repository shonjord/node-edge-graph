<?php declare(strict_types=1);

namespace App\Presentation\Http\Request;

use App\Presentation\Http\Response\ResponseTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestHandler implements RequestHandlerInterface
{
    use ResponseTrait;

    /**
     * @var array
     */
    private $stack;

    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    public function __construct(RequestHandlerInterface $handler)
    {
        $this->handler = $handler;
        $this->stack = [];
    }

    // adds a middleware to the stack
    public function addMiddleware(MiddlewareInterface $middleware) : void
    {
        $this->stack[] = $middleware;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $index
         * @var MiddlewareInterface $middleware
         */
        foreach ($this->stack as $index => $middleware) {
            $this->removeFromStack($index);
            break;
        }

        return $middleware->process($request, $this->stackIsEmpty() ? $this->handler : $this);
    }

    // removes middleware from stack
    private function removeFromStack(int $index): void
    {
        unset($this->stack[$index]);
    }

    // verifies if the current stack of middleware is empty
    private function stackIsEmpty(): bool
    {
        return empty($this->stack);
    }
}
