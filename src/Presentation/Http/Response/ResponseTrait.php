<?php declare(strict_types=1);

namespace App\Presentation\Http\Response;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    // returns a 200 ok with JSON header
    public function ok(array $content): ResponseInterface
    {
        return $this->jsonResponse(Response::HTTP_OK, $content);
    }

    // returns a 201 created with JSON header
    public function created(array $content): ResponseInterface
    {
        return $this->jsonResponse(Response::HTTP_CREATED, $content);
    }

    // generates a json response
    public function jsonResponse(int $code, array $content): ResponseInterface
    {
        $factory = new Psr17Factory();

        return $factory
            ->createResponse($code)
            ->withHeader('Content-type', 'application/json')
            ->withBody($factory->createStream(json_encode($content)));
    }
}
