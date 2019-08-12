<?php declare(strict_types=1);

namespace App\Domain\Person\Exception;

use App\Domain\Common\ValueObject\Uuid;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class PersonShortestPathNotFoundException extends Exception
{
    /**
     * @var string
     */
    public $message = 'shortest path for person with id: %s, not found';

    public function __construct(Uuid $id)
    {
        $this->message = sprintf($this->message, $id);

        parent::__construct($this->message, Response::HTTP_NOT_FOUND);
    }
}
