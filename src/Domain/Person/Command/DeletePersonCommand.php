<?php declare(strict_types=1);

namespace App\Domain\Person\Command;

use App\Domain\Common\ValueObject\Uuid;

class DeletePersonCommand
{
    /**
     * @var Uuid
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
    }

    // id of the person that should be deleted
    public function getId(): Uuid
    {
        return $this->id;
    }
}
