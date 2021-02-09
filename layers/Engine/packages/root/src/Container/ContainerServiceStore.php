<?php

declare(strict_types=1);

namespace PoP\Root\Container;

/**
 * Collect the services that must be automatically instantiated,
 * i.e. that no piece of code will explicitly reference, but whose
 * services must always be executed. Eg: hooks.
 */
class ContainerServiceStore
{
    /**
     * @var string[]
     */
    public static array $servicesToInstantiate = [];

    public static function addServiceToInstantiate(string $serviceToInstantiate): void
    {
        self::$servicesToInstantiate[] = $serviceToInstantiate;
    }

    /**
     * @return string[]
     */
    public static function getServicesToInstantiate(): array
    {
        return self::$servicesToInstantiate;
    }
}
