<?php declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

final class Name
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    // returns a string representation of $this name
    public function __toString(): string
    {
        return $this->value;
    }
}
