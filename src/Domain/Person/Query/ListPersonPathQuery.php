<?php declare(strict_types=1);

namespace App\Domain\Person\Query;

use App\Domain\Common\ValueObject\Uuid;

final class ListPersonPathQuery
{
    /**
     * @var Uuid
     */
    private $personID;

    /**
     * @var Uuid|null
     */
    private $personIDShortestPath;

    private function __construct(Uuid $personID)
    {
        $this->personID = $personID;
    }

    // generates a new instance of the query
    public static function fromArray(array $query): self
    {
        $self = new self(
            Uuid::fromString($query['personID'])
        );

        if (array_key_exists('person', $query)) {
            $self->personIDShortestPath = Uuid::fromString($query['person']);
        }

        return $self;
    }

    // returns the person ID
    public function getPersonID(): Uuid
    {
        return $this->personID;
    }

    // verifies if the given query should list path by personID
    public function shouldFindShortestPathOfPerson(): bool
    {
        return null !== $this->personIDShortestPath;
    }

    // returns the person that should be analyzed for the path
    public function getPersonIDShortestPath(): ?Uuid
    {
        return $this->personIDShortestPath;
    }
}
