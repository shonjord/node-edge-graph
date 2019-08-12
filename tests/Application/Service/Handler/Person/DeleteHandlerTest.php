<?php declare(strict_types=1);

namespace Test\Application\Service\Handler\Person;

use App\Application\Service\Handler\Person\DeletePersonHandler;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Command\DeletePersonCommand;
use App\Domain\Person\Exception\PersonDoesNotExistException;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class DeleteHandlerTest extends TestCase
{
    public function testThatPersonDoesNotExist(): void
    {
        $command = new DeletePersonCommand((string) Uuid::create());

        $repository = $this->prophesize(PersonRepositoryInterface::class);
        $repository
            ->findById($command->getId())
            ->shouldBeCalled()
            ->willReturn(null);
        $repository = $repository->reveal();

        $handler = new DeletePersonHandler($repository);
        $this->expectException(PersonDoesNotExistException::class);
        ($handler)($command);
    }
}
