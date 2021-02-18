<?php

declare(strict_types=1);

namespace PoP\Application;

use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            // \PoP\ComponentModelConfiguration\Component::class,
            \PoP\API\Component::class,
            \PoP\EmojiDefinitions\Component::class,
            \PoP\DefinitionPersistence\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        $packageName = basename(dirname(__DIR__));
        $folder = dirname(__DIR__, 2);
        return [
            $folder . '/migrate-' . $packageName . '/initialize.php',
        ];
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
        self::initYAMLServices(dirname(__DIR__));
    }

    /**
     * Initialize services for the system container
     *
     * @param array<string, mixed> $configuration
     */
    protected static function initializeSystemContainerServices(
        array $configuration = []
    ): void {
        parent::initializeSystemContainerServices($configuration);
        self::initYAMLSystemContainerServices(dirname(__DIR__));
    }
}
