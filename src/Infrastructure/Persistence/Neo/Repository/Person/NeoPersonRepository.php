<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Neo\Repository\Person;

use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Person\Entity\Person;
use App\Domain\Person\Exception\PersonAlreadyExistException;
use App\Domain\Person\Path\PersonPath;
use App\Domain\Person\Query\ListPersonPathQuery;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Stream\Entity\Stream;
use App\Infrastructure\Persistence\Neo\Repository\AbstractNeoRepository;
use App\Infrastructure\Persistence\Neo\Repository\Exception\ConstraintValidationException;
use GraphAware\Neo4j\Client\Formatter\RecordView;
use GraphAware\Neo4j\Client\Formatter\Result;
use GraphAware\Neo4j\Client\Formatter\Type\Node;
use GraphAware\Neo4j\Client\Formatter\Type\Path;
use GraphAware\Neo4j\Client\Formatter\Type\Relationship;

final class NeoPersonRepository extends AbstractNeoRepository implements PersonRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function save(Person $person): void
    {
        try {
            $this->create(sprintf(
                "{ id: '%s', name: '%s', email: '%s' }",
                $person->getId(),
                $person->getName(),
                $person->getEmail()
            ));
        } catch (ConstraintValidationException $exception) {
            throw new PersonAlreadyExistException($person);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findById(Uuid $id): ?Person
    {
        if (null === $node = $this->getNodeOf(sprintf("{ id: '%s' }", $id))) {
            return null;
        }

        $person = $node->values();
        $this->appendRelationsTo($person);

        return Person::fromArray($person);
    }

    /**
     * {@inheritDoc}
     */
    public function update(Person $person): void
    {
        if ($person->streamsWereAddedRecently()) {
            $this->linkPersonToStream($person);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Person $person): void
    {
        $this->delete(
            sprintf("{ id: '%s' }", $person->getId())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function findShortestPathOf(ListPersonPathQuery $query): ?PersonPath
    {
        if ($query->shouldFindShortestPathOfPerson()) {
            $person = $this->findById($query->getPersonID());
            $shortestPathPerson = $this->findById($query->getPersonIDShortestPath());

            if (null === $person || null === $shortestPathPerson) {
                return null;
            }

            /** @var Result $result */
            $result = $this->client->run(sprintf(
                "MATCH (a:Person), (b:Person),
                path = shortestPath((a)-[*..15]-(b))
                WHERE a.id = '%s'
                AND b.id = '%s'
                RETURN path",
                $person->getId(),
                $shortestPathPerson->getId()
            ));

            if (!$result->hasRecord()) {
                return null;
            }

            /** @var RecordView $records */
            $records = $result->getRecords()[0];
            /** @var Path $path */
            $path = $records->values()[0];
            $nodes = $path->nodes();
            $relations = $path->relationships();
            $edges = [];
            $shortest = '';

            /**
             * @var int $index
             * @var Node $node
             */
            foreach ($nodes as $index => $node) {
                if ($node->get('id') === (string) $shortestPathPerson->getId()) {
                    $shortest = $nodes[$index - 1]->get('name');
                }
            }

            /** @var Relationship $relation */
            foreach ($relations as $relation) {
                if (!in_array($relation->type(), $edges)) {
                    $edges[] = $relation->type();
                }
            }

            return new PersonPath(
                $person->getName(),
                $shortestPathPerson->getName(),
                $edges,
                new Name($shortest)
            );

        }

        return null;
    }

    // adds relations to the given person
    private function appendRelationsTo(array &$person): void
    {
        /** @var Result $relations */
        $relations = $this->client->run(sprintf(
            "MATCH ( person:Person {id : '%s'} )--(relations) RETURN relations",
            $person['id']
        ));

        /** @var RecordView $relation */
        foreach ($relations->getRecords() as $relation) {
            /** @var Node $node */
            $node = $relation->values()[0];
            $label = strtolower($node->labels()[0]);
            $person[$label][] = $node->values();
        }
    }

    // links the given person to the recently added streams
    private function linkPersonToStream(Person $person): void
    {
        $person->getRecentlyAddedStreams()->each(function (Stream $stream) use ($person) : void {
            $this->client->run(sprintf(
                "MATCH (p:Person), (s:Stream)
                WHERE p.id = '%s'
                AND s.id = '%s'
                CREATE (p)-[:WATCHES]->(s)
                RETURN p",
                $person->getId(),
                $stream->getId()
            ));
        });
    }
}
