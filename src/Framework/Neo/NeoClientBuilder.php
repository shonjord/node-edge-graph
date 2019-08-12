<?php declare(strict_types=1);

namespace App\Framework\Neo;

use GraphAware\Neo4j\Client\ClientBuilder;
use GraphAware\Neo4j\Client\ClientInterface;
use GraphAware\Neo4j\Client\HttpDriver\Configuration;
use Http\Message\MessageFactory\GuzzleMessageFactory;

final class NeoClientBuilder
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $uri;

    private function __construct(string $alias, string $uri)
    {
        $this->alias = $alias;
        $this->uri = $uri;
    }

    // builds a neo client
    public static function build(string $alias, string $uri): ClientInterface
    {
        return (new self($alias, $uri))->getClient();
    }

    // returns the generated NEO client
    private function getClient(): ClientInterface
    {
        $builder = new ClientBuilder();
        $builder->addConnection(
            $this->alias,
            $this->uri,
            Configuration::create(null, new GuzzleMessageFactory())
        );

        return $builder->build();
    }
}
