<?php declare(strict_types=1);

namespace App\Domain\Person\Command;

use App\Domain\Common\Collection\IdCollection;
use App\Domain\Common\ValueObject\Uuid;

final class CreatePersonStreamCommand
{
    /**
     * @var Uuid
     */
    private $personID;

    /**
     * @var IdCollection
     */
    private $streamIDs;

    public function __construct(string $personId, array $streamIDs)
    {
        $this->personID = Uuid::fromString($personId);
        $this->streamIDs = $this->toCollection($streamIDs);
    }

    // returns the id of the person that will receive a new stream
    public function getPersonID(): Uuid
    {
        return $this->personID;
    }

    // returns the stream IDs that should be added to the person
    public function getStreamIDs(): IdCollection
    {
        return $this->streamIDs;
    }

    // iterates through the given array and generates an ID collection
    private function toCollection(array $streamIDs): IdCollection
    {
        $collection = new IdCollection();

        foreach ($streamIDs as $id) {
            $collection->add(Uuid::fromString($id));
        }

        return $collection;
    }
}
