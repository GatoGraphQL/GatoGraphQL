<?php

declare(strict_types=1);

namespace PoPCMSSchema\SettingsWP\TypeAPIs;

use PoPCMSSchema\Settings\TypeAPIs\AbstractSettingsTypeAPI;

class SettingsTypeAPI extends AbstractSettingsTypeAPI
{
    /**
     * If the name is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetOption(string $name): mixed
    {
        return \get_option($name, null);
    }

    /**
     * @return string[]
     */
    public function getOptionNames(): array
    {
        global $wpdb;
        /** @var string[] */
        return $wpdb->get_col("SELECT option_name FROM {$wpdb->options}");
    }
}
