<?php declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

final class Description
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    // returns a string representation of $this description
    public function __toString(): string
    {
        return $this->value;
    }
}
