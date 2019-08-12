<?php declare(strict_types=1);

namespace Test\Application\Service\Person;

use App\Application\Service\Person\ListPersonPath;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Exception\PersonShortestPathNotFoundException;
use App\Domain\Person\Query\ListPersonPathQuery;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class ListPersonPathTest extends TestCase
{
    public function testThatPathWasNotFound(): void
    {
        $query = ListPersonPathQuery::fromArray([
            'personID' => (string) Uuid::create(),
            'person' => (string) Uuid::create()
        ]);

        $repository = $this->prophesize(PersonRepositoryInterface::class);
        $repository
            ->findShortestPathOf($query)
            ->shouldBeCalled()
            ->willReturn(null);
        $repository = $repository->reveal();

        $service = new ListPersonPath($repository);
        $this->expectException(PersonShortestPathNotFoundException::class);
        $service->list($query);
    }
}
