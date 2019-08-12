<?php declare(strict_types=1);

namespace Test\Application\Service\Handler\Person;

use App\Application\Service\Handler\Person\CreatePersonStreamHandler;
use App\Domain\Common\ValueObject\Description;
use App\Domain\Common\ValueObject\Email;
use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Command\CreatePersonStreamCommand;
use App\Domain\Person\Entity\Person;
use App\Domain\Person\Exception\PersonDoesNotExistException;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Stream\Entity\Stream;
use App\Domain\Stream\Exception\StreamDoesNotExistException;
use App\Domain\Stream\Exception\StreamIsAlreadyInCollectionException;
use App\Domain\Stream\Repository\StreamRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreatePersonStreamHandlerTest extends TestCase
{
    public function testThatPersonDoesNotExist(): void
    {
        $command = new CreatePersonStreamCommand((string) Uuid::create(), [(string) Uuid::create()]);
        $streamRepo = $this->prophesize(StreamRepositoryInterface::class);
        $streamRepo = $streamRepo->reveal();

        $personRepo = $this->prophesize(PersonRepositoryInterface::class);
        $personRepo
            ->findById($command->getPersonID())
            ->shouldBeCalled()
            ->willReturn(null);
        $personRepo = $personRepo->reveal();

        $handler = new CreatePersonStreamHandler($personRepo, $streamRepo);

        $this->expectException(PersonDoesNotExistException::class);
        ($handler)($command);
    }

    public function testThatStreamDoesNotExist(): void
    {
        $streamID = Uuid::create();
        $command = new CreatePersonStreamCommand((string) Uuid::create(), [(string) $streamID]);
        $streamRepo = $this->prophesize(StreamRepositoryInterface::class);
        $streamRepo
            ->findById($streamID)
            ->shouldBeCalled()
            ->willReturn(null);
        $streamRepo = $streamRepo->reveal();

        $person = new Person($command->getPersonID(), new Name('test'), new Email('test@test.com'));
        $personRepo = $this->prophesize(PersonRepositoryInterface::class);
        $personRepo
            ->findById($command->getPersonID())
            ->shouldBeCalled()
            ->willReturn($person);
        $personRepo = $personRepo->reveal();

        $handler = new CreatePersonStreamHandler($personRepo, $streamRepo);

        $this->expectException(StreamDoesNotExistException::class);
        ($handler)($command);
    }

    public function testThatStreamIsAlreadyInCollection(): void
    {
        $streamID = Uuid::create();
        $command = new CreatePersonStreamCommand((string) Uuid::create(), [(string) $streamID]);
        $streamRepo = $this->prophesize(StreamRepositoryInterface::class);
        $stream = new Stream($streamID, new Name('Netflix'), new Description('test'));
        $streamRepo
            ->findById($streamID)
            ->shouldBeCalled()
            ->willReturn($stream);
        $streamRepo = $streamRepo->reveal();

        $person = new Person($command->getPersonID(), new Name('test'), new Email('test@test.com'));
        $person->addStream($stream);

        $personRepo = $this->prophesize(PersonRepositoryInterface::class);
        $personRepo
            ->findById($command->getPersonID())
            ->shouldBeCalled()
            ->willReturn($person);
        $personRepo = $personRepo->reveal();

        $handler = new CreatePersonStreamHandler($personRepo, $streamRepo);

        $this->expectException(StreamIsAlreadyInCollectionException::class);
        ($handler)($command);
    }
}
