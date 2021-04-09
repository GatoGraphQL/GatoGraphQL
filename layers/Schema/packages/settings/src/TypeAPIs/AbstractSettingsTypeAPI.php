<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

use PoPSchema\Settings\ComponentConfiguration;
use PoPSchema\Settings\Constants\Behaviors;
use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

abstract class AbstractSettingsTypeAPI implements SettingsTypeAPIInterface
{
    final public function getOption(string $name): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         */
        $settingsEntries = ComponentConfiguration::getSettingsEntries();
        $settingsBehavior = ComponentConfiguration::getSettingsBehavior();
        if (
            ($settingsBehavior == Behaviors::ALLOWLIST && !in_array($name, $settingsEntries))
            || ($settingsBehavior == Behaviors::DENYLIST && in_array($name, $settingsEntries))
        ) {
            return null;
        }
        /**
         * Check if the allow/denylist validation by regex fails
         */
        if (
            in_array($settingsBehavior, [
                Behaviors::REGEX_ALLOWLIST,
                Behaviors::REGEX_DENYLIST,
            ])
        ) {
            $matchResults = array_filter(array_map(
                fn (string $regex): int | false => preg_match('/' . $regex . '/', $name),
                $settingsEntries
            ));
            if (
                ($settingsBehavior == Behaviors::REGEX_ALLOWLIST && count($matchResults) === 0)
                || ($settingsBehavior == Behaviors::REGEX_DENYLIST && count($matchResults) > 0)
            ) {
                return null;
            }
        }
        return $this->doGetOption($name);
    }

    abstract protected function doGetOption(string $name): mixed;
}
