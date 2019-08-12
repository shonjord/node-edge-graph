<?php declare(strict_types=1);

namespace App\Presentation\Http\Action\Person;

use App\CommandBus\Application\CommandBusInterface;
use App\Domain\Person\Command\DeletePersonCommand;
use App\Presentation\Http\Response\ResponseTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DeletePerson implements RequestHandlerInterface
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
        $query = (object) $request->getQueryParams();

        $this->handler->handle(
            new DeletePersonCommand($query->id)
        );

        return $this->ok([
            'status' => 'deleted'
        ]);
    }
}
