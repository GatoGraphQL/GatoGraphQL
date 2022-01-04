<?php

declare(strict_types=1);

namespace PoPSchema\Pages;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private ?int $getPageListDefaultLimit = 10;
    private ?int $getPageListMaxLimit = -1;
    private bool $addPageTypeToCustomPostUnionTypes = false;

    public function getPageListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::PAGE_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getPageListDefaultLimit;
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

    public function getPageListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::PAGE_LIST_MAX_LIMIT;
        $selfProperty = &$this->getPageListMaxLimit;
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

    public function addPageTypeToCustomPostUnionTypes(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES;
        $selfProperty = &$this->addPageTypeToCustomPostUnionTypes;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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
