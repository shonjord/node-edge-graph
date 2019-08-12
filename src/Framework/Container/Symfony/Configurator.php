<?php declare(strict_types=1);

namespace App\Framework\Container\Symfony;

use RuntimeException;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\LazyProxy\Instantiator\InstantiatorInterface;
use Symfony\Component\DependencyInjection\LazyProxy\PhpDumper\DumperInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Throwable;

final class Configurator
{
    /**
     * @const string
     */
    private const CLASS_FILE = 'class';

    /**
     * @const string
     */
    private const CACHE_CONTAINER = 'CacheContainer';

    /**
     * @var string
     */
    private $services;

    /**
     * @var ConfigCache
     */
    private $configCache;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var DumperInterface
     */
    private $dumper;

    public function __construct(string $services, string $cacheFile)
    {
        $this->services = $services;
        $this->configCache = new ConfigCache($cacheFile, true);
        $this->container = new ContainerBuilder;
    }

    // configures the container with the given callback
    public function configure(callable $callback) : void
    {
        if (!$this->configCache->isFresh()) {
            $callback($this);
        }
    }

    // writes the cache class with the given name
    public function writeClass(string $name): void
    {
        $this->configCache->write(
            $this->dumper->dump([static::CLASS_FILE => $name]),
            $this->container->getResources()
        );

        $this->cleanProperties();
    }

    // writes a cache container
    public function writeCacheContainerClass(): void
    {
        $this->writeClass(static::CACHE_CONTAINER);
    }

    // assigns a dumper to PHP dumper
    public function withDumper(DumperInterface $dumper): self
    {
        $phpDumper = new PhpDumper($this->container);
        $phpDumper->setProxyDumper($dumper);

        $this->dumper = $phpDumper;

        return $this;
    }

    // assigns a proxy dumper (which works for lazy load)
    public function withProxyDumper(): self
    {
        $this->withDumper(new ProxyDumper());

        return $this;
    }

    // compiles the container
    public function compile(): self
    {
        $this->container->compile(true);

        return $this;
    }

    // assigns a instantiator for the proxy manager
    public function withProxyInstantiator(InstantiatorInterface $instantiator): self
    {
        $this->container->setProxyInstantiator($instantiator);

        return $this;
    }

    // assigns runtime instantiator to the container
    public function withRuntimeInstantiator(): self
    {
        $this->withProxyInstantiator(new RuntimeInstantiator());

        return $this;
    }

    // loads the given yaml file of the services
    public function loadYamlServices(): self
    {
        try {
            (new YamlFileLoader(
                $this->container,
                new FileLocator(dirname($this->services))
            ))->load(basename($this->services));
        } catch (Throwable $exception) {
            throw new RuntimeException(
                sprintf('yml file: %s could not be loaded, because: %s', $this->services, $exception->getMessage())
            );
        }

        return $this;
    }

    // removes the dependencies so we don't store these in memory
    private function cleanProperties(): void
    {
        $this->services = null;
        $this->configCache = null;
        $this->container = null;
        $this->dumper = null;
    }
}
