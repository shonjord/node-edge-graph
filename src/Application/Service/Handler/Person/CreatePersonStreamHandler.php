<?php declare(strict_types=1);

namespace App\Application\Service\Handler\Person;

use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Command\CreatePersonStreamCommand;
use App\Domain\Person\Entity\Person;
use App\Domain\Person\Exception\PersonDoesNotExistException;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Stream\Entity\Stream;
use App\Domain\Stream\Exception\StreamDoesNotExistException;
use App\Domain\Stream\Repository\StreamRepositoryInterface;

final class CreatePersonStreamHandler
{
    /**
     * @var PersonRepositoryInterface
     */
    private $personRepo;

    /**
     * @var StreamRepositoryInterface
     */
    private $streamRepo;

    public function __construct(PersonRepositoryInterface $personRepo, StreamRepositoryInterface $streamRepo)
    {
        $this->personRepo = $personRepo;
        $this->streamRepo = $streamRepo;
    }

    // adds streams to a person
    public function __invoke(CreatePersonStreamCommand $command): void
    {
        $person = $this->getPersonFromID($command->getPersonID());

        $command->getStreamIDs()->each(function (Uuid $id) use ($person) : void {
            $person->addStream(
                $this->getStreamFromID($id)
            );
        });

        $this->personRepo->update($person);
    }

    // fetches person from DB, in case person does not exist, throws exception
    private function getPersonFromID(Uuid $id): Person
    {
        if (null === $person = $this->personRepo->findById($id)) {
            throw new PersonDoesNotExistException($id);
        }

        return $person;
    }

    // fetches stream from DB, in case stream does not exist, throws exception
    private function getStreamFromID(UUid $id): Stream
    {
        if (null === $stream = $this->streamRepo->findById($id)) {
            throw new StreamDoesNotExistException($id);
        }

        return $stream;
    }
}
