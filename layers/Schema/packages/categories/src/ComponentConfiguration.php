<?php

declare(strict_types=1);

namespace PoPSchema\Categories;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getCategoryListDefaultLimit = 10;
    private ?int $getCategoryListMaxLimit = -1;

    public function getCategoryListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CATEGORY_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getCategoryListDefaultLimit;
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

    public function getCategoryListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CATEGORY_LIST_MAX_LIMIT;
        $selfProperty = &$this->getCategoryListMaxLimit;
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
