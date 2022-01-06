<?php

declare(strict_types=1);

namespace PoP\CacheControl;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getDefaultCacheControlMaxAge(): int
    {
        // Define properties
        $envVariable = Environment::DEFAULT_CACHE_CONTROL_MAX_AGE;
        $defaultValue = 3600; // 1 hour
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
