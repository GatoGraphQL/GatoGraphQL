<?php

declare(strict_types=1);

namespace PoPSchema\Tags;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getTagListDefaultLimit = 10;
    private ?int $getTagListMaxLimit = -1;

    public function getTagListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::TAG_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getTagListDefaultLimit;
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

    public function getTagListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::TAG_LIST_MAX_LIMIT;
        $selfProperty = &$this->getTagListMaxLimit;
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
