<?php declare(strict_types=1);

namespace App\Presentation\Http\Action\Version;

use App\Presentation\Http\Response\ResponseTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetVersion implements RequestHandlerInterface
{
    use ResponseTrait;

    /**
     * @var string
     */
    private $app;

    /**
     * @var string
     */
    private $version;

    public function __construct(string $app, string $version)
    {
        $this->app = $app;
        $this->version = $version;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->ok([
            $this->app => $this->version
        ]);
    }
}
