<?php declare(strict_types=1);

namespace App\Domain\Person\Exception;

use App\Domain\Common\ValueObject\Uuid;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class PersonDoesNotExistException extends Exception
{
    /**
     * @var string
     */
    public $message = 'Person with the following id: %s, does not exist in the records';

    public function __construct(Uuid $id)
    {
        $this->message = sprintf($this->message, $id);

        parent::__construct($this->message, Response::HTTP_NOT_FOUND, null);
    }
}
