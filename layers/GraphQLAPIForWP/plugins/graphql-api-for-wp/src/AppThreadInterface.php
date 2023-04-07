<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;
use PoP\ComponentModel\AppThreadInterface as UpstreamAppThreadInterface;

interface AppThreadInterface extends UpstreamAppThreadInterface
{
    public function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
        ?ExtensionManager $extensionManager = null,
    ): void;

    public function getMainPluginManager(): MainPluginManager;
    public function getExtensionManager(): ExtensionManager;

    /**
     * Shortcut function.
     */
    public function getMainPlugin(): MainPluginInterface;

    /**
     * Shortcut function.
     */
    public function getExtension(string $extensionClass): ExtensionInterface;
}
