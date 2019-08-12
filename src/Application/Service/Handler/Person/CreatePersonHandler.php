<?php declare(strict_types=1);

namespace App\Application\Service\Handler\Person;

use App\Domain\Person\Command\CreatePersonCommand;
use App\Domain\Person\Entity\Person;
use App\Domain\Person\Repository\PersonRepositoryInterface;

final class CreatePersonHandler
{
    /**
     * @var PersonRepositoryInterface
     */
    private $repository;

    public function __construct(PersonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // adds a new person into the persistence layer
    public function __invoke(CreatePersonCommand $command): void
    {
        $this->repository->save(new Person(
            $command->getId(),
            $command->getName(),
            $command->getEmail()
        ));
    }
}
