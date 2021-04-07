<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

use PoPSchema\Settings\ComponentConfiguration;
use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

abstract class AbstractSettingsTypeAPI implements SettingsTypeAPIInterface
{
    final public function getOption(string $name): mixed
    {
        /**
         * Check that the option is either whitelisted, or not blacklisted
         */
        $settingsEntries = ComponentConfiguration::getSettingsEntries();
        $areSettingsBlacklisted = ComponentConfiguration::areSettingsEntriesBlacklisted();
        if (
            ($areSettingsBlacklisted && in_array($name, $settingsEntries))
            || (!$areSettingsBlacklisted && !in_array($name, $settingsEntries))
        ) {
            return null;
        }
        return $this->doGetOption($name);
    }

    abstract protected function doGetOption(string $name): mixed;
}
