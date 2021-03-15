<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

use PoP\Root\Component\AbstractComponent;

abstract class AbstractPluginComponent extends AbstractComponent
{
    protected static ?string $pluginFolder = null;

    public static function setPluginFolder(string $pluginFolder): void
    {
        self::$pluginFolder = $pluginFolder;
    }

    /**
     * Initialize services for the system container.
     * It uses Convention over Configuration: if the requested files exist,
     * load them.
     */
    protected static function initializeSystemContainerServices(): void
    {
        if (self::$pluginFolder === null) {
            return;
        }
        if (file_exists(self::$pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'system-services.yaml')) {
            self::initSystemServices(self::$pluginFolder);
        }
        if (file_exists(self::$pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'hybrid-services.yaml')) {
            self::initSystemServices(self::$pluginFolder, '', 'hybrid-services.yaml');
        }
    }

    /**
     * Initialize services
     * It uses Convention over Configuration: if the requested files exist,
     * load them.
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if (self::$pluginFolder === null) {
            return;
        }
        if (file_exists(self::$pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'services.yaml')) {
            self::initServices(self::$pluginFolder);
        }
        if (file_exists(self::$pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'hybrid-services.yaml')) {
            self::initServices(self::$pluginFolder, '', 'hybrid-services.yaml');
        }
        if (file_exists(self::$pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'schema-services.yaml')) {
            self::initSchemaServices(self::$pluginFolder, $skipSchema);
        }
    }
}
