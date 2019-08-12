<?php declare(strict_types=1);

namespace App\Presentation\Http\Action\Person;

use App\CommandBus\Application\CommandBusInterface;
use App\Domain\Person\Command\CreatePersonCommand;
use App\Presentation\Http\Response\ResponseTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CreatePerson implements RequestHandlerInterface
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

        $this->handler->handle(new CreatePersonCommand(
            $body->name,
            $body->email
        ));

        return $this->created([
            'status' => 'created'
        ]);
    }
}
