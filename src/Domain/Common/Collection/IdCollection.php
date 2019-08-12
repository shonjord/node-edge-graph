<?php declare(strict_types=1);

namespace App\Domain\Common\Collection;

use App\Domain\Common\ValueObject\Uuid;

final class IdCollection extends AbstractVector
{
    // appends new id to the collection
    public function add(Uuid $id): void
    {
        $this->vector->push($id);
    }
}
