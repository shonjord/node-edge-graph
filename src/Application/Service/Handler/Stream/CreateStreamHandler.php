<?php declare(strict_types=1);

namespace App\Application\Service\Handler\Stream;

use App\Domain\Stream\Command\CreateStreamCommand;
use App\Domain\Stream\Entity\Stream;
use App\Domain\Stream\Repository\StreamRepositoryInterface;

final class CreateStreamHandler
{
    /**
     * @var StreamRepositoryInterface
     */
    private $repository;

    public function __construct(StreamRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // adds a new stream into the persistence layer
    public function __invoke(CreateStreamCommand $command): void
    {
        $this->repository->save(new Stream(
            $command->getId(),
            $command->getName(),
            $command->getDescription()
        ));
    }
}
