<?php

declare(strict_types=1);

namespace PoPSchema\Media;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getMediaListDefaultLimit(): ?int
    {
        $envVariable = Environment::MEDIA_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getMediaListMaxLimit(): ?int
    {
        $envVariable = Environment::MEDIA_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
