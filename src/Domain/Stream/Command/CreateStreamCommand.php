<?php declare(strict_types=1);

namespace App\Domain\Stream\Command;

use App\Domain\Common\ValueObject\Description;
use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Uuid;

final class CreateStreamCommand
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Description
     */
    private $description;

    public function __construct(string $name, string $description)
    {
        $this->id = Uuid::create();
        $this->name = new Name($name);
        $this->description = new Description($description);
    }

    // returns the id of a new stream
    public function getId(): Uuid
    {
        return $this->id;
    }

    // returns the name of a new stream
    public function getName(): Name
    {
        return $this->name;
    }

    // returns the description of a new stream
    public function getDescription(): Description
    {
        return $this->description;
    }
}
