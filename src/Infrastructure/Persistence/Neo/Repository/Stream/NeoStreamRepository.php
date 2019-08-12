<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Neo\Repository\Language;

use App\Domain\Common\ValueObject\Uuid;
use App\Domain\Stream\Entity\Stream;
use App\Domain\Stream\Exception\StreamAlreadyExistsException;
use App\Domain\Stream\Repository\StreamRepositoryInterface;
use App\Infrastructure\Persistence\Neo\Repository\AbstractNeoRepository;
use App\Infrastructure\Persistence\Neo\Repository\Exception\ConstraintValidationException;

final class NeoStreamRepository extends AbstractNeoRepository implements StreamRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function save(Stream $stream): void
    {
        try {
            $this->create(sprintf(
                "{ id: '%s', name: '%s', description: '%s' }",
                $stream->getId(),
                $stream->getName(),
                $stream->getDescription()
            ));
        } catch (ConstraintValidationException $exception) {
            throw new StreamAlreadyExistsException($stream);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findById(Uuid $id): ?Stream
    {
        if (null === $node = $this->getNodeOf(sprintf("{ id: '%s' }", $id))) {
            return null;
        }

        return Stream::fromArray($node->values());
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Stream $stream): void
    {
        $this->delete(
            sprintf("{ id: '%s' }", $stream->getId())
        );
    }
}
