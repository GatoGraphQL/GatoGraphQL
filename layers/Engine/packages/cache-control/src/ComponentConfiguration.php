<?php

declare(strict_types=1);

namespace PoP\CacheControl;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getDefaultCacheControlMaxAge(): int
    {
        $envVariable = Environment::DEFAULT_CACHE_CONTROL_MAX_AGE;
        $defaultValue = 3600; // 1 hour
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
