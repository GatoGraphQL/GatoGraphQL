<?php

declare(strict_types=1);

namespace PoP\CacheControl;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private int $getDefaultCacheControlMaxAge = 3600;

    public function getDefaultCacheControlMaxAge(): int
    {
        // Define properties
        $envVariable = Environment::DEFAULT_CACHE_CONTROL_MAX_AGE;
        $selfProperty = &$this->getDefaultCacheControlMaxAge;
        $defaultValue = 3600; // 1 hour
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
