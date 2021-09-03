<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\ConfigurationCache;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;

class ServiceContainerCacheConfigurationManager extends AbstractCacheConfigurationManager
{
    /**
     * The timestamp from when last saving settings/modules to the DB
     */
    protected function getTimestamp(): int
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        return $userSettingsManager->getServiceContainerTimestamp();
    }
}
