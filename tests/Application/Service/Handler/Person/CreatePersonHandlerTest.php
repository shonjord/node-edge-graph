<?php declare(strict_types=1);

namespace Test\Application\Service\Handler\Person;

use App\Application\Service\Handler\Person\CreatePersonHandler;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Command\CreatePersonCommand;
use App\Domain\Person\Entity\Person;
use App\Domain\Person\Exception\PersonAlreadyExistException;
use App\Domain\Person\Path\PersonPath;
use App\Domain\Person\Query\ListPersonPathQuery;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreatePersonHandlerTest extends TestCase
{
    public function testThatUserAlreadyExists(): void
    {
        $repository = new class implements PersonRepositoryInterface {
            /** {@inheritDoc} */
            public function save(Person $person): void
            {
                throw new PersonAlreadyExistException($person);
            }
            /** {@inheritDoc} */
            public function findById(Uuid $id): ?Person
            {
                return null;
            }
            /** {@inheritDoc} */
            public function remove(Person $person): void
            {
                return;
            }
            /** {@inheritDoc} */
            public function update(Person $person): void
            {
                return;
            }
            /** {@inheritDoc} */
            public function findShortestPathOf(ListPersonPathQuery $query): ?PersonPath
            {
                return null;
            }
        };

        $handler = new CreatePersonHandler($repository);
        $command = new CreatePersonCommand('Test', 'test@test.com');

        $this->expectException(PersonAlreadyExistException::class);
        ($handler)($command);
    }
}
