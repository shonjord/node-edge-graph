<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Neo\Repository;

use App\Infrastructure\Persistence\Neo\Repository\Exception\ConstraintValidationException;
use GraphAware\Common\Result\Result;
use GraphAware\Neo4j\Client\ClientInterface;
use GraphAware\Neo4j\Client\Formatter\Type\Node;
use Throwable;

abstract class AbstractNeoRepository
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $node;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    // sets the node of the repository
    public function setNode(string $node): void
    {
        $this->node = $node;
    }

    /**
     * @param string $params
     * @throws ConstraintValidationException
     * @throws Throwable
     */
    protected function create(string $params): void
    {
        try {
            $this->client->run(
                sprintf("CREATE (%s:%s %s)", $this->getLoweredNode(), $this->node, $params)
            );
        } catch (Throwable $exception) {
            $this->parseException($exception);
        }
    }

    protected function delete(string $params): void
    {
        $this->client->run(sprintf(
            "MATCH (%s:%s %s) DELETE %s",
            $this->getLoweredNode(),
            $this->node,
            $params,
            $this->getLoweredNode()
        ));
    }

    // returns the first node of the result
    protected function getNodeOf(string $params): ?Node
    {
        $result = $this->getResultOf($params);

        if (!empty($result->records())) {
            return $result->firstRecord()->values()[0];
        }

        return null;
    }

    // returns a result for the given params
    protected function getResultOf(string $params): Result
    {
        return $this->client->run(sprintf(
            "MATCH (%s:%s %s) RETURN %s",
            $this->getLoweredNode(),
            $this->node,
            $params,
            $this->getLoweredNode()
        ));
    }

    // returns in lower case the node
    protected function getLoweredNode(): string
    {
        return strtolower($this->node);
    }

    // parses the neo exception into a more manageable exception
    protected function parseException(Throwable $exception): void
    {
        $parts = explode('.', $exception->getMessage());

        foreach ($parts as $message) {
            if (strpos($message, "ConstraintValidationFailed") !== false) {
                throw new ConstraintValidationException();
            }
        }

        throw $exception;
    }
}
