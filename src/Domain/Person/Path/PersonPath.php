<?php declare(strict_types=1);

namespace App\Domain\Person\Path;

use App\Domain\Common\ValueObject\Name;

final class PersonPath
{
    /**
     * @var Name
     */
    private $from;

    /**
     * @var Name
     */
    private $to;

    /**
     * @var array
     */
    private $edges;

    /**
     * @var Name
     */
    private $shortest;

    public function __construct(Name $from, Name $to, array $edges, Name $shortest)
    {
        $this->from = $from;
        $this->to = $to;
        $this->edges = $edges;
        $this->shortest = $shortest;
    }

    // returns an array representation of person path
    public function toArray(): array
    {
        return [
            'from' => (string) $this->from,
            'to' => (string) $this->to,
            'edges' => $this->edges,
            'shortest' => (string) $this->shortest
        ];
    }
}
