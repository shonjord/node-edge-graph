<?php declare(strict_types=1);

namespace App\Framework\League\LeagueCommandBus;

use App\CommandBus\Application\CommandBusInterface;
use League\Tactician\CommandBus as LeagueCommandBus;

final class CommandBus implements CommandBusInterface
{
    /**
     * @var LeagueCommandBus
     */
    private $commandBus;

    public function __construct(LeagueCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(object $object): void
    {
        $this->commandBus->handle($object);
    }
}
