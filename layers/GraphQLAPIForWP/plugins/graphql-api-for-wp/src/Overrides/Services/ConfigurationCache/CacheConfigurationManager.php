<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\Services\ConfigurationCache;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;

/**
 * Inject configuration to the cache
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CacheConfigurationManager implements CacheConfigurationManagerInterface
{
    public function __construct(private EndpointHelpers $endpointHelpers)
    {
    }

    /**
     * Save into the DB, and inject to the FilesystemAdapter:
     * A string used as the subdirectory of the root cache directory, where cache
     * items will be stored
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     */
    public function getNamespace(): string
    {
        $mainPluginVersion = (string) MainPluginManager::getConfig('version');
        // (Needed for development) Don't share cache among plugin versions
        $timestamp = '_v' . $mainPluginVersion;
        // The timestamp from when last saving settings/modules to the DB
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $timestamp .= '_' . $userSettingsManager->getTimestamp();
        // admin/non-admin screens have different services enabled
        $suffix = \is_admin() ?
            // The WordPress editor can access the full GraphQL schema,
            // including "unrestricted" admin fields, so cache it individually
            'a' . ($this->endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint() ? 'u' : 'c')
            : 'c';
        $timestamp .= '_' . $suffix;
        return $timestamp;
    }

    /**
     * Cache under the plugin's cache/ subfolder
     */
    public function getDirectory(): ?string
    {
        $mainPluginCacheDir = (string) MainPluginManager::getConfig('cache-dir');
        return $mainPluginCacheDir . \DIRECTORY_SEPARATOR . 'config-via-symfony-cache';
    }
}
