<?php declare(strict_types=1);

namespace App\Domain\Person\Repository;

use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Entity\Person;
use App\Domain\Person\Path\PersonPath;
use App\Domain\Person\Query\ListPersonPathQuery;

interface PersonRepositoryInterface
{
    // saves a new person to the persistence layer
    public function save(Person $person): void;

    // finds a person with the given id
    public function findById(Uuid $id): ?Person;

    // removes a person from the persistence layer
    public function remove(Person $person): void;

    // updates a person
    public function update(Person $person): void;

    // gets the shortest path
    public function findShortestPathOf(ListPersonPathQuery $query): ?PersonPath;
}
