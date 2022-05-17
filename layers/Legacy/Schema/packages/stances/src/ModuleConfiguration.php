<?php

declare(strict_types=1);

namespace PoPSchema\Stances;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\ComponentModel\ModuleConfiguration\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    private ?int $getStanceListDefaultLimit = 10;
    private ?int $getStanceListMaxLimit = -1;

    public function getStanceListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::STANCE_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getStanceListDefaultLimit;
        $defaultValue = 10;
        $callback = EnvironmentValueHelpers::toInt(...);

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function getStanceListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::STANCE_LIST_MAX_LIMIT;
        $selfProperty = &self::$getStanceListMaxLimit;
        $defaultValue = -1; // Unlimited
        $callback = EnvironmentValueHelpers::toInt(...);

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
