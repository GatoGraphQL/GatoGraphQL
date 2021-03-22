<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl;

use PoP\AccessControl\Component as AccessControlComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use CanDisableComponentTrait;

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\UserRoles\Component::class,
            \PoPSchema\UserStateAccessControl\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\CacheControl\Component::class,
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
        if (self::isEnabled()) {
            self::initServices(dirname(__DIR__));
            self::initSchemaServices(dirname(__DIR__), $skipSchema);

            if (class_exists('\PoP\CacheControl\Component')) {
                self::initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses),
                    '/Conditional/CacheControl'
                );
            }
        }
    }

    protected static function resolveEnabled()
    {
        return AccessControlComponent::isEnabled();
    }
}
