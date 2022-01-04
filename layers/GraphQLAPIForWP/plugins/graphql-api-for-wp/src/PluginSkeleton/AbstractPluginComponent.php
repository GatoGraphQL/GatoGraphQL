<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use PoP\Root\Component\AbstractComponent;

abstract class AbstractPluginComponent extends AbstractComponent implements PluginComponentInterface
{
    /**
     * @var string[]
     */
    private static array $pluginFolders = [];

    public static function setPluginFolder(string $pluginFolder): void
    {
        self::$pluginFolders[get_called_class()] = $pluginFolder;
    }

    public static function getPluginFolder(): ?string
    {
        return self::$pluginFolders[get_called_class()] ?? null;
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
        /**
         * ModuleResolvers are also hybrid, but they are defined on a different config
         * to make it easier to understand (for documentation)
         */
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'module-services.yaml')) {
            self::initSystemServices($pluginFolder, '', 'module-services.yaml');
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
        /**
         * ModuleResolvers are also hybrid, but they are defined on a different config
         * to make it easier to understand (for documentation)
         */
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'module-services.yaml')) {
            self::initServices($pluginFolder, '', 'module-services.yaml');
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'services.yaml')) {
            self::initServices($pluginFolder);
        }
        if (file_exists($pluginFolder . \DIRECTORY_SEPARATOR . 'config' . \DIRECTORY_SEPARATOR . 'schema-services.yaml')) {
            self::initSchemaServices($pluginFolder, $skipSchema);
        }
    }
}
