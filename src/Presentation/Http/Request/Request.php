<?php declare(strict_types=1);

namespace App\Presentation\Http\Request;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class Request implements ServerRequestInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    private function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    // generates a server request from globals
    public static function fromGlobals(): self
    {
        $factory = new Psr17Factory();
        $requestCreator = new ServerRequestCreator($factory, $factory, $factory, $factory);

        return new self(
            $requestCreator->fromGlobals()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return $this->request->getProtocolVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        return new self(
            $this->request->withProtocolVersion($version)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return $this->request->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        return $this->request->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        return $this->request->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        return new self(
            $this->request->withHeader($name, $value)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        return new self(
            $this->request->withAddedHeader($name, $value)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        return new self(
            $this->request->withoutHeader($name)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->request->getBody();
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        return new self(
            $this->request->withBody($body)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTarget()
    {
        return $this->request->getRequestTarget();
    }

    /**
     * {@inheritDoc}
     */
    public function withRequestTarget($requestTarget)
    {
        return new self(
            $this->request->withRequestTarget($requestTarget)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * {@inheritDoc}
     */
    public function withMethod($method)
    {
        return new self(
            $this->request->withMethod($method)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getUri()
    {
        return $this->request->getUri();
    }

    /**
     * {@inheritDoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self(
            $this->request->withUri($uri, $preserveHost)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServerParams()
    {
        return $this->request->getServerParams();
    }

    /**
     * {@inheritDoc}
     */
    public function getCookieParams()
    {
        return $this->request->getCookieParams();
    }

    /**
     * {@inheritDoc}
     */
    public function withCookieParams(array $cookies)
    {
        return new self(
            $this->request->withCookieParams($cookies)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParams()
    {
        return $this->request->getQueryParams();
    }

    /**
     * {@inheritDoc}
     */
    public function withQueryParams(array $query)
    {
        return new self(
            $this->request->withQueryParams($query)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFiles()
    {
        return $this->request->getUploadedFiles();
    }

    /**
     * {@inheritDoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return new self(
            $this->request->withUploadedFiles($uploadedFiles)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    /**
     * {@inheritDoc}
     */
    public function withParsedBody($data)
    {
        return new self(
            $this->request->withParsedBody($data)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->request->getAttributes();
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute($name, $value)
    {
        return new self(
            $this->request->withAttribute($name, $value)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($name)
    {
        return new self(
            $this->request->withoutAttribute($name)
        );
    }
}
