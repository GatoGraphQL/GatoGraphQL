<?php

declare(strict_types=1);

namespace PoP\TranslationWP;

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
            \PoP\Translation\Component::class,
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
        self::initServices(dirname(__DIR__));
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
    {
        // The same services injected into the application are injected into the system container
        self::initSystemServices(dirname(__DIR__), '', 'services.yaml');
    }
}
