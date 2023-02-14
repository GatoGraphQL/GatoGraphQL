<?php

declare(strict_types=1);

namespace PoPCMSSchema\Settings;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * @return string[]
     */
    public function getSettingsEntries(): array
    {
        $envVariable = Environment::SETTINGS_ENTRIES;
        $defaultValue = [];
        $callback = EnvironmentValueHelpers::commaSeparatedStringToArray(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getSettingsBehavior(): string
    {
        $envVariable = Environment::SETTINGS_BEHAVIOR;
        $defaultValue = Behaviors::ALLOW;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
