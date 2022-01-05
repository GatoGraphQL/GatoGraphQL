<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConfigurationCache;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInfoInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use PoP\BasicService\BasicServiceTrait;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;

/**
 * Inject configuration to the cache
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
abstract class AbstractCacheConfigurationManager implements CacheConfigurationManagerInterface
{
    use BasicServiceTrait;

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?EndpointHelpers $endpointHelpers = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
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
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();
        // (Needed for development) Don't share cache among plugin versions
        $timestamp = '_v' . $mainPluginVersion;
        // The timestamp from when last saving settings/modules to the DB
        $timestamp .= '_' . $this->getTimestamp();
        // admin/non-admin screens have different services enabled
        $suffix = \is_admin() ?
            // The WordPress editor can access the full GraphQL schema,
            // including "admin" fields, so cache it individually
            'a' . ($this->getEndpointHelpers()->isRequestingAdminFixedSchemaGraphQLEndpoint() ? 'u' : 'c')
            : 'c';
        $timestamp .= '_' . $suffix;
        return $timestamp;
    }

    /**
     * The timestamp from when last saving settings/modules to the DB
     */
    abstract protected function getTimestamp(): int;

    /**
     * Cache under the plugin's cache/ subfolder
     */
    public function getDirectory(): ?string
    {
        /** @var MainPluginInfoInterface */
        $mainPluginInfo = App::getMainPlugin()->getInfo();
        $mainPluginCacheDir = $mainPluginInfo->getCacheDir();
        return $mainPluginCacheDir . \DIRECTORY_SEPARATOR . $this->getDirectoryName();
    }

    /**
     * Cache under the plugin's cache/ subfolder
     */
    abstract protected function getDirectoryName(): string;
}
