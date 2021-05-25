<?php

declare(strict_types=1);

namespace PoP\EngineWP;

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
            \PoP\Engine\Component::class,
            \PoP\RoutingWP\Component::class,
            \PoP\HooksWP\Component::class,
            \PoP\TranslationWP\Component::class,
        ];
    }

    public static function getTemplatesDir(): string
    {
        return dirname(__DIR__) . '/templates';
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
}
