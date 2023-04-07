<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;
use PoP\ComponentModel\AppThread as ComponentModelAppThread;

class AppThread extends ComponentModelAppThread implements AppThreadInterface
{
    protected MainPluginManager $mainPluginManager;
    protected ExtensionManager $extensionManager;

    public function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
        ?ExtensionManager $extensionManager = null,
    ): void {
        $this->mainPluginManager = $mainPluginManager ?? $this->createMainPluginManager();
        $this->extensionManager = $extensionManager ?? $this->createExtensionManager();
    }

    protected function createExtensionManager(): ExtensionManager
    {
        return new ExtensionManager();
    }

    protected function createMainPluginManager(): MainPluginManager
    {
        return new MainPluginManager();
    }

    public function getMainPluginManager(): MainPluginManager
    {
        return $this->mainPluginManager;
    }

    public function getExtensionManager(): ExtensionManager
    {
        return $this->extensionManager;
    }

    /**
     * Shortcut function.
     */
    public function getMainPlugin(): MainPluginInterface
    {
        return $this->getMainPluginManager()->getPlugin();
    }

    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ExtensionInterface> $extensionClass
     */
    public function getExtension(string $extensionClass): ExtensionInterface
    {
        return $this->getExtensionManager()->getExtension($extensionClass);
    }
}
