<?php declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use InvalidArgumentException;
use JsonSchema\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

final class JsonSchemaValidator implements MiddlewareInterface
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var array
     */
    private $schemas;

    /**
     * @var ?string
     */
    private $schema;

    public function __construct(Validator $validator, array $schemas)
    {
        $this->validator = $validator;
        $this->schemas = $schemas;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $body = $request->getParsedBody();

        if ($this->requestRequiresSchemaValidation($request) && $this->bodyHasInvalidSchema($body)) {
            throw new InvalidArgumentException(
                $this->getErrors(),
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->resetValidator();

        return $handler->handle($request);
    }

    // verifies if the given path requires a schema validation
    private function requestRequiresSchemaValidation(ServerRequestInterface $request): bool
    {
        $path = sprintf('%s-%s', $request->getMethod(), $request->getUri()->getPath());

        if (array_key_exists($path, $this->schemas)) {
            $this->schema = $this->schemas[$path];
        }

        return is_string($this->schema);
    }

    // verifies if the validation fails for the given path and body content
    private function bodyHasInvalidSchema(object $body): bool
    {
        $this->validator->validate($body, [
            '$ref' => sprintf('file://%s', $this->schema)
        ]);

        $this->schema = null;

        return ! $this->validator->isValid();
    }

    private function getErrors(): string
    {
        $errors = $this->validator->getErrors();

        $this->resetValidator();

        return json_encode($errors);
    }

    private function resetValidator(): void
    {
        $this->validator->reset();
    }
}
