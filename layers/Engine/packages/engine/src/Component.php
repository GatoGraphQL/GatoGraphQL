<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Root\Component\AbstractComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

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
            \PoP\Hooks\Component::class,
            \PoP\Translation\Component::class,
            \PoP\LooseContracts\Component::class,
            \PoP\Routing\Component::class,
            \PoP\ModuleRouting\Component::class,
            \PoP\ComponentModel\Component::class,
            \PoP\CacheControl\Component::class,
            \PoP\GuzzleHelpers\Component::class,
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
        ComponentConfiguration::setConfiguration($configuration);
        self::initServices(dirname(__DIR__));
        self::initServices(dirname(__DIR__), '/Overrides');
        self::initSchemaServices(dirname(__DIR__), $skipSchema);
        if (!Environment::disableGuzzleOperators()) {
            self::initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/Guzzle');
        }
        if (ComponentModelComponentConfiguration::useComponentModelCache()) {
            self::initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/UseComponentModelCache');
        }
    }
}
