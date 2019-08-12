<?php declare(strict_types=1);

namespace App\Domain\Person\Exception;

use App\Domain\Person\Entity\Person;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class PersonAlreadyExistException extends Exception
{
    /**
     * @var string
     */
    public $message = 'Person with the following email: %s, already exists';

    public function __construct(Person $person)
    {
        $this->message = sprintf($this->message, $person->getEmail());

        parent::__construct($this->message, Response::HTTP_CONFLICT);
    }
}
