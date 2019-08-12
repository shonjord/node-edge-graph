<?php declare(strict_types=1);

namespace App\Domain\Common\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class InvalidEmailAddressException extends Exception
{
    /**
     * @var string
     */
    public $message = 'email address: %s, is invalid';

    public function __construct(string $value)
    {
        $this->message = sprintf($this->message, $value);

        parent::__construct($this->message, Response::HTTP_CONFLICT);
    }
}
