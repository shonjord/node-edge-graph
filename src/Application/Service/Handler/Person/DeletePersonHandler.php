<?php declare(strict_types=1);

namespace App\Application\Service\Handler\Person;

use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Command\DeletePersonCommand;
use App\Domain\Person\Entity\Person;
use App\Domain\Person\Exception\PersonDoesNotExistException;
use App\Domain\Person\Repository\PersonRepositoryInterface;

final class DeletePersonHandler
{
    /**
     * @var PersonRepositoryInterface
     */
    private $repository;

    public function __construct(PersonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // deletes a person from the persistence layer
    public function __invoke(DeletePersonCommand $command): void
    {
        $this->repository->remove(
            $this->getPersonFromId($command->getId())
        );
    }

    // returns a person from the persistence layer
    private function getPersonFromId(Uuid $id): ?Person
    {
        if (null === $person = $this->repository->findById($id)) {
            throw new PersonDoesNotExistException($id);
        }

        return $person;
    }
}
