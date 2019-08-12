<?php declare(strict_types=1);

namespace App\Framework\Router\Symfony;

use App\Framework\Router\RouterInterface;
use App\Presentation\Http\Action\LazyRequestHandler;
use App\Presentation\Http\Middleware\LazyMiddleware;
use App\Presentation\Http\Request\RequestHandler;
use App\Presentation\Http\Response\ResponseTrait;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router as SymfonyRouter;
use Throwable;

final class Router implements RouterInterface
{
    use ResponseTrait;

    /**
     * @const string
     */
    private const BEFORE_STACK = 'before_stack';

    /**
     * @const string
     */
    private const CONTROLLER = '_controller';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var SymfonyRouter
     */
    private $router;

    public function __construct(ContainerInterface $container, string $routes, string $cacheDir)
    {
        $this->container = $container;
        $this->router = new SymfonyRouter(new YamlFileLoader(new FileLocator()), $routes, ['cache_dir' => $cacheDir]);
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(ServerRequestInterface $request) : ResponseInterface
    {
        $request = $this->toSymfonyRequest($request);
        $this->setContextToRouterUsingRequest($request);
        try {
            $parameters = $this->router->matchRequest($request);
        } catch (Throwable $exception) {
            return $this->processRouterException($exception, $request);
        }

        $request = $this->toPsrRequest($request);

        // adding attributes info of the route to the request
        foreach ($parameters as $parameter => $value) {
            if (!is_array($value)) {
                $request = $request->withAttribute($parameter, $value);
            }
        }

        $controllerID = $parameters[static::CONTROLLER];

        try {
            if ($this->parametersBeforeStackIsEmpty($parameters)) {
                return $this->getHandler($controllerID)->handle($request);
            }

            $handler = new RequestHandler(
                $this->createLazyRequestHandler($controllerID)
            );

            foreach ($parameters[static::BEFORE_STACK] as $middlewareID) {
                $handler->addMiddleware(
                    $this->createLazyMiddleware($middlewareID)
                );
            }

            return $handler->handle($request);
        } catch (Throwable $exception) {
            return $this->processHandlerException($exception);
        }
    }

    private function createLazyMiddleware(string $id): LazyMiddleware
    {
        return new LazyMiddleware(
            function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($id) : ResponseInterface {
                return $this->getMiddleware($id)->process($request, $handler);
            }
        );
    }

    private function getMiddleware(string $id): MiddlewareInterface
    {
        return $this->container->get($id);
    }

    private function createLazyRequestHandler(string $id): RequestHandlerInterface
    {
        return new LazyRequestHandler(
            function (ServerRequestInterface $request) use ($id) : ResponseInterface {
                return $this->getHandler($id)->handle($request);
            }
        );
    }

    private function getHandler(string $id): RequestHandlerInterface
    {
        return $this->container->get($id);
    }

    private function parametersBeforeStackIsEmpty(array $parameters): bool
    {
        return !array_key_exists(static::BEFORE_STACK, $parameters);
    }

    private function setContextToRouterUsingRequest(Request $request): void
    {
        $this->router->setContext(
            (new RequestContext())->fromRequest($request)
        );
    }

    private function toPsrRequest(Request $request): ServerRequestInterface
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        return $psrHttpFactory->createRequest($request);
    }

    private function toSymfonyRequest(ServerRequestInterface $request): Request
    {
        return (new HttpFoundationFactory())->createRequest($request);
    }

    // returns an error response from the handler
    private function processHandlerException(Throwable $exception): ResponseInterface
    {
        $message = json_decode($exception->getMessage());
        $exceptionCode = $exception->getCode();
        $code = 0 === $exceptionCode ? Response::HTTP_INTERNAL_SERVER_ERROR : $exceptionCode;

        return $this->jsonResponse($code, [
            "code" => $code,
            "error" => json_last_error() === JSON_ERROR_NONE ? $message : $exception->getMessage(),
        ]);
    }

    private function processRouterException(Throwable $exception, Request $request): ResponseInterface
    {
        switch (true) {
            case $exception instanceof ResourceNotFoundException:
                $code = Response::HTTP_NOT_FOUND;
                $message = sprintf('resource not found: %s', $request->getPathInfo());
                break;
            case $exception instanceof MethodNotAllowedException:
                $code = Response::HTTP_BAD_REQUEST;
                $message = 'method not allowed for this request';
                break;
            case $exception instanceof NoConfigurationException:
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = 'routing configuration not found';
                break;
            default:
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = 'unknown internal server error';
        }

        return $this->jsonResponse($code, [
            "code" => $code,
            "error" => $message
        ]);
    }
}
