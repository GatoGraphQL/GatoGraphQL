<?php

declare(strict_types=1);

namespace PoPSchema\Menus;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getMenuListDefaultLimit = 10;
    private ?int $getMenuListMaxLimit = -1;

    public function getMenuListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MENU_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getMenuListDefaultLimit;
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

    public function getMenuListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MENU_LIST_MAX_LIMIT;
        $selfProperty = &$this->getMenuListMaxLimit;
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
}
