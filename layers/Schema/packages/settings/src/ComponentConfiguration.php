<?php

declare(strict_types=1);

namespace PoPSchema\Settings;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * @return string[]
     */
    public function getSettingsEntries(): array
    {
        $envVariable = Environment::SETTINGS_ENTRIES;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getSettingsBehavior(): string
    {
        $envVariable = Environment::SETTINGS_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
