<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\PluginManagement\ExtensionManager;
use GatoGraphQL\GatoGraphQL\PluginManagement\MainPluginManager;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInterface;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\MainPluginInterface;

interface PluginAppInterface
{
    public static function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
        ?ExtensionManager $extensionManager = null,
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
