<?php declare(strict_types=1);

namespace App\Domain\Language\Collection;

use App\Domain\Common\Collection\AbstractVector;
use App\Domain\Stream\Entity\Stream;
use App\Domain\Stream\Exception\StreamIsAlreadyInCollectionException;

final class StreamCollection extends AbstractVector
{
    // adds a new stream to the collection
    public function add(Stream $stream): void
    {
        $this->each(function (Stream $existing) use ($stream) : void {
            if ($stream->equals($existing)) {
                throw new StreamIsAlreadyInCollectionException($stream);
            }
        });

        $this->push($stream);
    }
}
