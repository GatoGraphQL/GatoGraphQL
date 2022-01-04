<?php

declare(strict_types=1);

namespace PoP\Site;

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
    public function getDependedComponentClasses(): array
    {
        return [
            \PoP\Application\Component::class,
            \PoP\Base36Definitions\Component::class,
            \PoP\ResourceLoader\Component::class,
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
        self::initServices(dirname(__DIR__), '/Overrides');
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
    {
        self::initSystemServices(dirname(__DIR__));
    }
}
