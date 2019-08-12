<?php declare(strict_types=1);

namespace Test\Application\Service\Handler\Stream;

use App\Application\Service\Handler\Person\CreatePersonHandler;
use App\Application\Service\Handler\Stream\CreateStreamHandler;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Command\CreatePersonCommand;
use App\Domain\Person\Exception\PersonAlreadyExistException;
use App\Domain\Stream\Command\CreateStreamCommand;
use App\Domain\Stream\Entity\Stream;
use App\Domain\Stream\Exception\StreamAlreadyExistsException;
use App\Domain\Stream\Repository\StreamRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreateStreamHandlerTest extends TestCase
{
    public function testThatStreamAlreadyExists(): void
    {
        $repository = new class implements StreamRepositoryInterface {
            public function save(Stream $stream): void
            {
                throw new StreamAlreadyExistsException($stream);
            }
            public function findById(Uuid $id): ?Stream
            {
                return null;
            }
            public function remove(Stream $person): void
            {
                return;
            }
        };

        $handler = new CreateStreamHandler($repository);
        $command = new CreateStreamCommand('Netflix', 'to watch movies');

        $this->expectException(StreamAlreadyExistsException::class);
        ($handler)($command);
    }
}
