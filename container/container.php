<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Framework\Container\Symfony\Configurator;

(new Configurator(
    __DIR__ .  '/../config/container/services.yml',
    __DIR__ . '/../cache/CacheContainer.php'
))->configure(function (Configurator $configurator) : void {
    // this happens only if config cache is not fresh
    $configurator
        ->loadYamlServices()
        ->withRuntimeInstantiator()
        ->compile()
        ->withProxyDumper()
        ->writeCacheContainerClass();
});

require_once __DIR__ . '/../cache/CacheContainer.php';

return new CacheContainer();
