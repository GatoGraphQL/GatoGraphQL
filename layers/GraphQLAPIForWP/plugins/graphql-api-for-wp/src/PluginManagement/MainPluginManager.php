<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPlugin;

class MainPluginManager
{
    private static AbstractMainPlugin $mainPlugin;

    public static function register(AbstractMainPlugin $mainPlugin): AbstractMainPlugin
    {
        self::$mainPlugin = $mainPlugin;
        return $mainPlugin;
    }

    /**
     * Get the configuration for the main plugin
     *
     * @return array<string, mixed>
     */
    public static function getConfig(): array
    {
        return self::$mainPlugin->getConfig();
    }

    /**
     * Get a configuration value for the main plugin
     *
     * @return array<string, mixed>
     */
    public static function getConfigValue(string $key): mixed
    {
        $mainPluginConfig = self::getConfig();
        return $mainPluginConfig[$key];
    }
}
