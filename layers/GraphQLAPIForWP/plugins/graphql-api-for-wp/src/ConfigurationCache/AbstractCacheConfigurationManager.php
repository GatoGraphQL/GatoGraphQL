<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConfigurationCache;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\PluginApp;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInfoInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

use function sanitize_file_name;

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
        /** @var EndpointHelpers */
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
        // (Needed for development) Don't share cache among plugin versions
        $timestamp = '_v' . $this->getMainPluginAndExtensionsTimestamp();
        // The timestamp from when last saving settings/modules to the DB
        $timestamp .= '_' . $this->getTimestamp();
        /**
         * admin/non-admin screens have different services enabled.
         */
        if (\is_admin()) {
            /**
             * Different admin endpoints might also have different services,
             * hence cache a different container per endpointGroup.
             *
             * For instance, the WordPress editor can access the full schema,
             * including "admin" fields, so it must be cached individually.
             *
             * @var string
             */
            $endpointGroup = $this->getEndpointHelpers()->getAdminGraphQLEndpointGroup();
            $suffix = 'a' . ($endpointGroup !== '' ? '_' . sanitize_file_name($endpointGroup) : '');
        } else {
            $suffix = 'c';
        }
        $timestamp .= '_' . $suffix;
        return $timestamp;
    }

    /**
     * Create a combination of the versions + commit hashes (for dev)
     * for the Main Plugin and Extensions. This way, when deploying
     * a new Plugin or Extension to InstaWP during DEV, the container
     * will be regenerated.
     *
     * Please notice: the version with hash contains "#", which is
     * forbidden in the folder name, but this problem is avoided
     * when doing `md5` of the string.
     */
    protected function getMainPluginAndExtensionsTimestamp(): string
    {
        $pluginVersions = [];
        $pluginVersions[] = PluginApp::getMainPlugin()->getPluginVersionWithCommitHash();
        foreach (PluginApp::getExtensionManager()->getExtensions() as $extensionInstance) {
            $pluginVersions[] = $extensionInstance->getPluginVersionWithCommitHash();
        }
        $pluginVersion = hash('md5', implode('|', $pluginVersions));

        // Just the first 8 characters is enough
        return substr($pluginVersion, 0, 8);
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
        $mainPluginInfo = PluginApp::getMainPlugin()->getInfo();
        $mainPluginCacheDir = $mainPluginInfo->getCacheDir();
        return $mainPluginCacheDir . \DIRECTORY_SEPARATOR . $this->getDirectoryName();
    }

    /**
     * Cache under the plugin's cache/ subfolder
     */
    abstract protected function getDirectoryName(): string;
}
