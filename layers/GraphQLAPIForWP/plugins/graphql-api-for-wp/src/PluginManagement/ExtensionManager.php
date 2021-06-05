<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtension;

class ExtensionManager extends AbstractPluginManager
{
    /**
     * @var array<string, AbstractExtension>
     */
    private static array $extensionClassInstances = [];

    public static function register(AbstractExtension $extension): AbstractExtension
    {
        $extensionClass = get_class($extension);

        /**
         * Validate it hasn't been registered yet, as to
         * make sure this plugin is not duplicated.
         */
        if (isset(self::$extensionClassInstances[$extensionClass])) {
            self::printAdminNoticeErrorMessage(
                sprintf(
                    __('Plugin <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'),
                    $extension->getConfig('name'),
                    self::$extensionClassInstances[$extensionClass]->getConfig('version'),
                    $extension->getConfig('version'),
                )
            );
            return self::$extensionClassInstances[$extensionClass];
        }

        self::$extensionClassInstances[$extensionClass] = $extension;
        return $extension;
    }

    /**
     * Get the configuration for an extension
     *
     * @return array<string, mixed>
     */
    public static function getFullConfiguration(string $extensionClass): array
    {
        $extensionInstance = self::$extensionClassInstances[$extensionClass];
        return $extensionInstance->getFullConfiguration();
    }

    /**
     * Get a configuration value for an extension
     *
     * @return array<string, mixed>
     */
    public static function getConfig(string $extensionClass, string $key): mixed
    {
        $extensionConfig = self::getFullConfiguration($extensionClass);
        return $extensionConfig[$key];
    }
}
