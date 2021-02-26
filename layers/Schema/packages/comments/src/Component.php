<?php

declare(strict_types=1);

namespace PoPSchema\Comments;

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
            \PoPSchema\CustomPosts\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\RESTAPI\Component::class,
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
        self::initServices(dirname(__DIR__));
        self::initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
            self::initServices(dirname(__DIR__), '/Conditional/RESTAPI');
        }

        if (class_exists('\PoPSchema\Users\Component')) {
            self::initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoPSchema\Users\Component::class, $skipSchemaComponentClasses),
                '/Conditional/Users'
            );
        }
    }
}
