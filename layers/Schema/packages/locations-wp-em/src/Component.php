<?php

declare(strict_types=1);

namespace PoPSchema\LocationsWPEM;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Root\Component\CanDisableComponentTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait, CanDisableComponentTrait;
    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\Locations\Component::class,
            \PoP\EngineWP\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'pop-schema/migrate-locations-wp-em',
        ];
    }

    /**
     * Enable if plugin Events Manager is installed
     *
     * @return void
     * @todo Fix: This code is executed before plugins are loaded, so `defined('EM_VERSION')` returns always `false`. Temporarily return always `true`
     */
    protected static function resolveEnabled()
    {
        return true;
        return defined('EM_VERSION');
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if (self::isEnabled()) {
            parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
            self::initYAMLServices(dirname(__DIR__));
        }
    }
}
