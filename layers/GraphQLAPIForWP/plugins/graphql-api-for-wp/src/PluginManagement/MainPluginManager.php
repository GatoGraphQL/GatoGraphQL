<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPlugin;

class MainPluginManager extends AbstractPluginManager
{
    private static ?AbstractMainPlugin $mainPlugin = null;

    /**
     * If the plugin is already registered, return null
     */
    public static function register(AbstractMainPlugin $mainPlugin): AbstractMainPlugin
    {
        self::$mainPlugin = $mainPlugin;
        return $mainPlugin;
    }

    /**
     * Validate that the plugin is not registered yet.
     * If it is, print an error and return false
     */
    public static function assertNotRegistered(
        string $pluginVersion
    ): bool {
        if (self::$mainPlugin !== null) {
            self::printAdminNoticeErrorMessage(
                sprintf(
                    __('Plugin <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'),
                    self::$mainPlugin->getConfig('name'),
                    self::$mainPlugin->getConfig('version'),
                    $pluginVersion,
                )
            );
            return false;
        }

        return true;
    }

    /**
     * Get the configuration for the main plugin
     *
     * @return array<string, mixed>
     */
    protected static function getFullConfiguration(): array
    {
        return self::$mainPlugin->getFullConfiguration();
    }

    /**
     * Get a configuration value for the main plugin
     *
     * @return array<string, mixed>
     */
    public static function getConfig(string $key): mixed
    {
        $mainPluginConfig = self::getFullConfiguration();
        return $mainPluginConfig[$key];
    }
}
