<?php declare(strict_types=1);

namespace App\Domain\Stream\Exception;

use App\Domain\Stream\Entity\Stream;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class StreamIsAlreadyInCollectionException extends Exception
{
    /**
     * @var string
     */
    public $message = 'stream %s is already part of the collection';

    public function __construct(Stream $stream)
    {
        $this->message = sprintf($this->message, $stream->getName());

        parent::__construct($this->message, Response::HTTP_CONFLICT);
    }
}