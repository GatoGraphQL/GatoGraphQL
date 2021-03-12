<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP;

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
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
            \GraphQLByPoP\GraphQLServer\Component::class,
            \PoPSchema\Media\Component::class,
            \PoPSchema\TranslateDirective\Component::class,
            \PoPSchema\CDNDirective\Component::class,
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
        self::initSchemaServices(dirname(__DIR__), $skipSchema);
        if (ComponentModelComponentConfiguration::useComponentModelCache()) {
            self::initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/UseComponentModelCache');
        }
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
    {
        parent::initializeSystemContainerServices();
        self::initSystemServices(dirname(__DIR__));
    }
}
