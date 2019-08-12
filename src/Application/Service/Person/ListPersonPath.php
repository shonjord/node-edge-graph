<?php declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Domain\Person\Exception\PersonShortestPathNotFoundException;
use App\Domain\Person\Path\PersonPath;
use App\Domain\Person\Query\ListPersonPathQuery;
use App\Domain\Person\Repository\PersonRepositoryInterface;

final class ListPersonPath implements ListPersonPathInterface
{
    /**
     * @var PersonRepositoryInterface
     */
    private $repository;

    public function __construct(PersonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function list(ListPersonPathQuery $query): PersonPath
    {
        if (null === $path = $this->repository->findShortestPathOf($query)) {
            throw new PersonShortestPathNotFoundException($query->getPersonID());
        }

        return $path;
    }
}
