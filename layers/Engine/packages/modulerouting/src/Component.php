<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

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
            \PoP\Root\Component::class,
        ];
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
