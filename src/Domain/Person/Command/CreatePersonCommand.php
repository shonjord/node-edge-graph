<?php declare(strict_types=1);

namespace App\Domain\Person\Command;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Uuid;

final class CreatePersonCommand
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
     * @var Email
     */
    private $email;

    public function __construct(string $name, string $email)
    {
        $this->id = Uuid::create();
        $this->name = new Name($name);
        $this->email = new Email($email);
    }

    // returns the id for a new person
    public function getId(): Uuid
    {
        return $this->id;
    }

    // returns the name for a new person
    public function getName(): Name
    {
        return $this->name;
    }

    // returns the e-mail of a new person
    public function getEmail(): Email
    {
        return $this->email;
    }
}
