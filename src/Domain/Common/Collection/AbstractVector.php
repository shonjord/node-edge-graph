<?php declare(strict_types=1);

namespace App\Domain\Common\Collection;

use Ds\Vector;

abstract class AbstractVector
{
    /**
     * @var Vector
     */
    protected $vector;

    /**
     * @var Vector
     */
    protected $added;

    /**
     * @var Vector
     */
    protected $removed;

    public function __construct(array $container = null)
    {
        $this->vector = new Vector(null === $container ? [] : $container);
        $this->added =  new Vector();
        $this->removed = new Vector();
    }

    // iterates through the container
    public function each(callable $callback): void
    {
        foreach ($this->vector as $value) {
            $callback($value);
        }
    }

    // adds values to the collections
    public function push(object $value): void
    {
        $this->vector->push($value);
        $this->added->push($value);
    }

    // verifies if there were any recent values added to the collection
    public function wereAddedRecently(): bool
    {
        return !$this->added->isEmpty();
    }

    // get the recent values added to the collection
    /** @return static */
    public function getRecentAdded(): self
    {
        return new static(
            $this->added->toArray()
        );
    }
}
