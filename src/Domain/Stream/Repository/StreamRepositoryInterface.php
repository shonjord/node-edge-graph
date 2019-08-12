<?php declare(strict_types=1);

namespace App\Domain\Stream\Repository;

use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Stream\Entity\Stream;

interface StreamRepositoryInterface
{
    // saves a new stream to the persistence layer
    public function save(Stream $stream): void;

    // finds a stream
    public function findById(Uuid $id): ?Stream;

    // removes a stream from the persistence layer
    public function remove(Stream $person): void;
}
