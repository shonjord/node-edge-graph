<?php declare(strict_types=1);

namespace App\Domain\Person\Entity;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Language\Collection\StreamCollection;
use App\Domain\Person\Collection\PersonCollection;
use App\Domain\Stream\Entity\Stream;

final class Person
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var StreamCollection
     */
    private $streams;

    /**
     * @var PersonCollection
     */
    private $friends;

    public function __construct(Uuid $id, Name $name, Email $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->streams = new StreamCollection();
        $this->friends = new PersonCollection();
    }

    // returns the ID associated with $this person
    public function getId(): Uuid
    {
        return $this->id;
    }

    // returns the Name associated with $this person
    public function getName(): Name
    {
        return $this->name;
    }

    // returns the Name associated with $this person
    public function getEmail(): Email
    {
        return $this->email;
    }

    // append a new stream to $this person's stream collection
    public function addStream(Stream $stream): void
    {
        $this->streams->add($stream);
    }

    // verifies if there were added streams recently to the collection
    public function streamsWereAddedRecently(): bool
    {
        return $this->streams->wereAddedRecently();
    }

    // returns the recently added streams to the collection
    public function getRecentlyAddedStreams(): StreamCollection
    {
        return $this->streams->getRecentAdded();
    }

    // creates a new instance of a person from the given array
    public static function fromArray(array $person): self
    {
        $self = new self(
            Uuid::fromString($person['id']),
            new Name($person['name']),
            new Email($person['email'])
        );

        if (array_key_exists('stream', $person)) {
            $streams = [];
            foreach ($person['stream'] as $stream) {
                $streams[] = Stream::fromArray($stream);
            }

            $self->streams = new StreamCollection($streams);
        }

        return $self;
    }
}
