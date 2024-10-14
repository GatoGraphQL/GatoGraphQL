<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Overrides\ConfigurationCache;

use GatoGraphQL\GatoGraphQL\ConfigurationCache\AbstractCacheConfigurationManager;

class OperationalCacheConfigurationManager extends AbstractCacheConfigurationManager
{
    /**
     * The timestamp from when last saving settings/modules to the DB
     */
    protected function getUniqueTimestamp(): string
    {
        return $this->getUserSettingsManager()->getOperationalUniqueTimestamp();
    }

    /**
     * Cache under the plugin's cache/ subfolder
     */
    protected function getDirectoryName(): string
    {
        return 'operational';
    }
}
