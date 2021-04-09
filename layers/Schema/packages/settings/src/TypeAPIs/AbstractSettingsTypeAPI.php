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
         * Compare for full match or regex
         */
        $settingsEntries = ComponentConfiguration::getSettingsEntries();
        $settingsBehavior = ComponentConfiguration::getSettingsBehavior();
        $matchResults = array_filter(array_map(
            function (string $termOrRegex) use ($name): bool {
                // Check if it is a regex expression
                if (str_starts_with($termOrRegex, '/') && str_ends_with($termOrRegex, '/')) {
                    return preg_match($termOrRegex, $name) === 1;
                }
                // Check it's a full match
                return $termOrRegex === $name;
            },
            $settingsEntries
        ));
        if (
            ($settingsBehavior == Behaviors::ALLOWLIST && count($matchResults) === 0)
            || ($settingsBehavior == Behaviors::DENYLIST && count($matchResults) > 0)
        ) {
            return null;
        }
        return $this->doGetOption($name);
    }

    abstract protected function doGetOption(string $name): mixed;
}
