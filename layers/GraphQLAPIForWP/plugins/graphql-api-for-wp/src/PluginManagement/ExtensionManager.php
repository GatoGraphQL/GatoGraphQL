<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtension;

class ExtensionManager extends AbstractPluginManager
{
    /**
     * Have the extensions organized by their class
     *
     * @var array<string, AbstractExtension>
     */
    private static array $extensionClassInstances = [];

    /**
     * Have the extensions organized by their baseName
     *
     * @var array<string, AbstractExtension>
     */
    private static array $extensionBaseNameInstances = [];

    /**
     * @return array<string, AbstractExtension>
     */
    public static function getExtensions(): array
    {
        return self::$extensionBaseNameInstances;
    }

    public static function register(AbstractExtension $extension): AbstractExtension
    {
        $extensionClass = get_class($extension);
        self::$extensionClassInstances[$extensionClass] = $extension;
        self::$extensionBaseNameInstances[$extension->getPluginBaseName()] = $extension;
        return $extension;
    }

    /**
     * Validate that the extension is not registered yet.
     * If it is, print an error and return false
     */
    public static function assertNotRegistered(
        string $extensionClass,
        string $extensionVersion
    ): bool {
        if (isset(self::$extensionClassInstances[$extensionClass])) {
            self::printAdminNoticeErrorMessage(
                sprintf(
                    __('Extension <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'),
                    self::$extensionClassInstances[$extensionClass]->getConfig('name'),
                    self::$extensionClassInstances[$extensionClass]->getConfig('version'),
                    $extensionVersion,
                )
            );
            return false;
        }

        return true;
    }

    /**
     * Get the configuration for an extension
     *
     * @return array<string, mixed>
     */
    protected static function getFullConfiguration(string $extensionClass): array
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
