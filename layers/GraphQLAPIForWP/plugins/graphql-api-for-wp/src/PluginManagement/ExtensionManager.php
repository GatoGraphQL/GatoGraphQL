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
        self::$extensionClassInstances[get_class($extension)] = $extension;
        return $extension;
    }

    /**
     * Get the configuration for an extension
     *
     * @return array<string, mixed>
     */
    public static function getConfig(string $extensionClass): array
    {
        $extensionInstance = self::$extensionClassInstances[$extensionClass];
        return $extensionInstance->getConfig();
    }

    /**
     * Get a configuration value for an extension
     *
     * @return array<string, mixed>
     */
    public static function getConfigValue(string $extensionClass, string $key): mixed
    {
        $extensionConfig = self::getConfig($extensionClass);
        return $extensionConfig[$key];
    }
}
