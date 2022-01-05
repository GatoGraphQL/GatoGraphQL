<?php

declare(strict_types=1);

namespace PoPSchema\Pages;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getPageListDefaultLimit = 10;
    private ?int $getPageListMaxLimit = -1;
    private bool $addPageTypeToCustomPostUnionTypes = false;

    public function getPageListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::PAGE_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getPageListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::PAGE_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function addPageTypeToCustomPostUnionTypes(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
