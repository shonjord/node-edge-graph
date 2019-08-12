<?php declare(strict_types=1);

namespace App\Presentation\Http\Action\Stream;

use App\CommandBus\Application\CommandBusInterface;
use App\Domain\Stream\Command\CreateStreamCommand;
use App\Presentation\Http\Response\ResponseTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CreateStream implements RequestHandlerInterface
{
    use ResponseTrait;

    /**
     * @var CommandBusInterface
     */
    private $handler;

    public function __construct(CommandBusInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->handler->handle(new CreateStreamCommand(
            $body->name,
            $body->description
        ));

        return $this->created([
            'status' => 'created'
        ]);
    }
}
