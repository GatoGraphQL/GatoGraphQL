<?php

declare(strict_types=1);

namespace PoPSchema\Settings;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private array $getSettingsEntries = [];
    private string $getSettingsBehavior = Behaviors::ALLOWLIST;

    public function getSettingsEntries(): array
    {
        // Define properties
        $envVariable = Environment::SETTINGS_ENTRIES;
        $selfProperty = &$this->getSettingsEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getSettingsBehavior(): string
    {
        // Define properties
        $envVariable = Environment::SETTINGS_BEHAVIOR;
        $selfProperty = &$this->getSettingsBehavior;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
