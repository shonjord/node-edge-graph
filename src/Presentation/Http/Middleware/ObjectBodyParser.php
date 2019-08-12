<?php declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

final class ObjectBodyParser implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request->withParsedBody(
            $this->getBodyFromRequest($request)
        ));
    }

    // returns a body object from the given request
    private function getBodyFromRequest(ServerRequestInterface $request): object
    {
        $body = $request->getParsedBody();

        if (empty($body) && null === $body = $this->decodeBody($request->getBody())) {
            throw new InvalidArgumentException(
                "body should not be empty for this action",
                Response::HTTP_BAD_REQUEST
            );
        }

        return (object) $body;
    }

    // decodes a given body (in stream format) and returns the content
    private function decodeBody(StreamInterface $body): ?object
    {
        return json_decode($body->getContents());
    }
}
