<?php declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid
{
    /**
     * @var RamseyUuid
     */
    private $value;

    // returns a new instance of the UUID
    public static function create(): self
    {
        $id = new static;
        $id->value = RamseyUuid::uuid4();

        return $id;
    }

    // returns a new instance of the UUID based on the given string
    public static function fromString(string $value): self
    {
        $id = new static;
        $id->value = RamseyUuid::fromString($value);

        return $id;
    }

    // verifies if the given value equals to $this
    public function equals(Uuid $id): bool
    {
        return (string) $this->value === (string) $id;
    }

    // returns a string representation of $this UUID
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
