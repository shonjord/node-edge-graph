<?php declare(strict_types=1);

namespace App\Domain\Stream\Entity;

use App\Domain\Common\ValueObject\Description;
use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Uuid;

final class Stream
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
     * @var Description
     */
    private $description;

    public function __construct(Uuid $id, Name $name, Description $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    // returns the ID associated with $this stream
    public function getId(): Uuid
    {
        return $this->id;
    }

    // returns the name associated with $this stream
    public function getName(): Name
    {
        return $this->name;
    }

    // returns the description associated with $this stream
    public function getDescription(): Description
    {
        return $this->description;
    }

    // verifies if the given stream is the same as $this stream
    public function equals(Stream $stream): bool
    {
        return $this->id->equals($stream->id);
    }

    // returns a new instance from a given array
    public static function fromArray(array $stream): self
    {
        return new self(
            Uuid::fromString($stream['id']),
            new Name($stream['name']),
            new Description($stream['description'])
        );
    }
}
