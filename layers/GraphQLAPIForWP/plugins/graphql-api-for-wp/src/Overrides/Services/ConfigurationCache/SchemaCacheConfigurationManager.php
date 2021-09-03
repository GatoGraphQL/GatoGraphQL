<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\Services\ConfigurationCache;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\Services\ConfigurationCache\AbstractCacheConfigurationManager;

class SchemaCacheConfigurationManager extends AbstractCacheConfigurationManager
{
    /**
     * The timestamp from when last saving settings/modules to the DB
     */
    protected function getTimestamp(): int
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        return $userSettingsManager->getSchemaTimestamp();
    }
}
