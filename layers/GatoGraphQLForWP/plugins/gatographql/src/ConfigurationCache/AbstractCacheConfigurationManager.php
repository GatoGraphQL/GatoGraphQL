<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConfigurationCache;

use GatoGraphQL\GatoGraphQL\Constants\AdminGraphQLEndpointGroups;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\MainPluginInfoInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

use function is_admin;
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
        if ($this->endpointHelpers === null) {
            /** @var EndpointHelpers */
            $endpointHelpers = $this->instanceManager->getInstance(EndpointHelpers::class);
            $this->endpointHelpers = $endpointHelpers;
        }
        return $this->endpointHelpers;
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
        return $this->makeNamespace(
            $this->getNamespaceTimestampPrefix(),
            $this->getNamespaceSuffix()
        );
    }

    protected function getNamespaceSuffix(): string
    {
        /**
         * admin/non-admin screens have different services enabled.
         *
         * By checking for `is_admin` we are also store the container
         * for internal execution, via the `GraphQLServer` class, and
         * the cache will be shared with the "default" private endpoint.
         */
        if (!is_admin()) {
            /**
             * Single endpoint / Custom endpoints / Persisted queries
             */
            return 'public';
        }

        /**
         * Admin endpoints.
         *
         * Notice that Persisted Queries in the admin are also handled
         * here, and not under "public", since they'll also have the services
         * registered on ConditionalOnContext\Admin (even if they won't
         * be used).
         */
        $suffix = 'private';

        /**
         * Different admin endpoints might also have different services,
         * hence cache a different container per endpointGroup.
         *
         * For instance, the WordPress editor can access the full schema,
         * including "admin" fields, so it must be cached individually.
         */
        $endpointGroup = $this->getEndpointHelpers()->getAdminGraphQLEndpointGroup();

        /**             *
         * All admin endpoints either have (such as PluginOwnUse,
         * which adds a FieldResolver for field "schemaConfigurations")
         * or are allowed to have (eg: Custom Admin Endpoint, via hook)
         * a distinctive configuration of their own, so cache them
         * independently.
         *
         * This also applies to Persisted Query endpoints. Normally,
         * they are the same as the Default admin endpoint (they are
         * applied the same Disabled Modules, so they have the same
         * Service Container) with the unique exception of
         * Low-Level Persisted Query Editing, so then can't reuse the cache.
         */
        if ($endpointGroup !== AdminGraphQLEndpointGroups::DEFAULT) {
            $suffix .= '_' . sanitize_file_name($endpointGroup);
        }
        return $suffix;
    }

    protected function makeNamespace(string $prefix, string $suffix): string
    {
        return $prefix . '_' . $suffix;
    }

    protected function getNamespaceTimestampPrefix(): string
    {
        // (Needed for development) Don't share cache among plugin versions
        $timestampPrefix = '_v' . $this->getMainPluginAndExtensionsTimestamp();
        // The timestamp from when last saving settings/modules to the DB
        $timestampPrefix .= '_' . $this->getUniqueTimestamp();
        return $timestampPrefix;
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
    abstract protected function getUniqueTimestamp(): string;

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
