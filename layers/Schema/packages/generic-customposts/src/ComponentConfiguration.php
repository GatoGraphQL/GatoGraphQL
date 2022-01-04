<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getGenericCustomPostListDefaultLimit = 10;
    private ?int $getGenericCustomPostListMaxLimit = -1;
    private array $getGenericCustomPostTypes = ['post'];

    public function getGenericCustomPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getGenericCustomPostListDefaultLimit;
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

    public function getGenericCustomPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT;
        $selfProperty = &$this->getGenericCustomPostListMaxLimit;
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

    public function getGenericCustomPostTypes(): array
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_TYPES;
        $selfProperty = &$this->getGenericCustomPostTypes;
        $defaultValue = ['post'];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

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
