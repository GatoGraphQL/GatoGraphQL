<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\ConfigurationCache\Overrides;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;

/**
 * Inject configuration to the cache
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CacheConfigurationManager implements CacheConfigurationManagerInterface
{
    /**
     * Save into the DB, and inject to the FilesystemAdapter:
     * A string used as the subdirectory of the root cache directory, where cache
     * items will be stored
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     */
    public function getNamespace(): string
    {
        // (Needed for development) Don't share cache among plugin versions
        $timestamp = '_v' . \GRAPHQL_API_VERSION;
        // The timestamp from when last saving settings/modules to the DB
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $timestamp .= '_' . $userSettingsManager->getTimestamp();
        // admin/non-admin screens have different services enabled
        if (\is_admin()) {
            $timestamp .= '_admin';
        }
        return $timestamp;
    }
}
