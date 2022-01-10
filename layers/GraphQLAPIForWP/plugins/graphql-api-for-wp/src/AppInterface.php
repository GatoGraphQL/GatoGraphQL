<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;

interface AppInterface
{
    public static function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
    ): void;

    public static function getMainPluginManager(): MainPluginManager;
    public static function getExtensionManager(): ExtensionManager;

    /**
     * Shortcut function.
     */
    public static function getMainPlugin(): MainPluginInterface;

    /**
     * Shortcut function.
     */
    public static function getExtension(string $extensionClass): ExtensionInterface;
}
