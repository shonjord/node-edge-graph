<?php declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Domain\Person\Path\PersonPath;
use App\Domain\Person\Query\ListPersonPathQuery;

interface ListPersonPathInterface
{
    // returns the shortest path between person and X node
    public function list(ListPersonPathQuery $query): PersonPath;
}
