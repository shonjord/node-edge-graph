<?php declare(strict_types=1);

namespace App\CommandBus\Application;

interface CommandBusInterface
{
    // handles a given object
    public function handle(object $object): void;
}
