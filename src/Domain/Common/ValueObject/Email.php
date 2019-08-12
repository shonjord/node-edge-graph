<?php declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Exception\InvalidEmailAddressException;

final class Email
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $this->getValid($value);
    }

    // returns a string representation of $this email
    public function __toString(): string
    {
        return $this->value;
    }

    // verifies if the given value is a valid e-mail, if not, throws an exception
    private function getValid(string $value) : string
    {
        if ($this->isValid($value)) {
            return $value;
        }
        throw new InvalidEmailAddressException($value);
    }

    // verifies if the given value is a valid e-mail address
    private function isValid(string $value) : bool
    {
        $value = preg_replace('/\s+/', '', $value);

        if (is_bool(filter_var(strtolower($value), FILTER_VALIDATE_EMAIL))) {
            return false;
        }
        return true;
    }
}
