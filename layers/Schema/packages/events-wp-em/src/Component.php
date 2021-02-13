<?php

declare(strict_types=1);

namespace PoPSchema\EventsWPEM;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoPSchema\Events\Environment;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use CanDisableComponentTrait;

    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\Events\Component::class,
            \PoPSchema\CustomPostsWP\Component::class,
            \PoPSchema\LocationsWPEM\Component::class,
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
            if (Environment::addEventTypeToCustomPostUnionTypes()) {
                self::maybeInitPHPSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/AddEventTypeToCustomPostUnionTypes/Overrides');
            }
        }
    }
}
