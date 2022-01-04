<?php

declare(strict_types=1);

namespace PoPSchema\Media;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getMediaListDefaultLimit = 10;
    private ?int $getMediaListMaxLimit = -1;

    public function getMediaListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MEDIA_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getMediaListDefaultLimit;
        $defaultValue = 10;
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

    public function getMediaListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MEDIA_LIST_MAX_LIMIT;
        $selfProperty = &$this->getMediaListMaxLimit;
        $defaultValue = -1; // Unlimited
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
