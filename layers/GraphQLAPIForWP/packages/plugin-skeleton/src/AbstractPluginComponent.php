<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

use PoP\Root\Component\AbstractComponent;

abstract class AbstractPluginComponent extends AbstractComponent
{
    /**
     * @var string[]
     */
    private static array $pluginFolders = [];

    public static function setPluginFolder(string $pluginFolder): void
    {
        static::$pluginFolders[get_called_class()] = $pluginFolder;
    }

    public static function getPluginFolder(): ?string
    {
        return static::$pluginFolders[get_called_class()] ?? null;
    }

    /**
     * Initialize services for the system container.
     * It uses Convention over Configuration: if the requested files exist,
     * load them.
     */
    protected static function initializeSystemContainerServices(): void
    {
        $pluginFolder = static::getPluginFolder();
        if ($pluginFolder === null) {
            return;
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'hybrid-services.yaml')) {
            self::initSystemServices($pluginFolder, '', 'hybrid-services.yaml');
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'system-services.yaml')) {
            self::initSystemServices($pluginFolder);
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
        $pluginFolder = static::getPluginFolder();
        if ($pluginFolder === null) {
            return;
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'hybrid-services.yaml')) {
            self::initServices($pluginFolder, '', 'hybrid-services.yaml');
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'services.yaml')) {
            self::initServices($pluginFolder);
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'schema-services.yaml')) {
            self::initSchemaServices($pluginFolder, $skipSchema);
        }
    }
}
