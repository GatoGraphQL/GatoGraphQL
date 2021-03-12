<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\ConvertCaseDirectives\Component::class,
        ];
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
    {
        self::initSystemServices(dirname(__DIR__), '', 'hybrid-services.yaml');
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
        self::initServices(dirname(__DIR__), '', 'hybrid-services.yaml');
    }
}
