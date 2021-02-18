<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP;

use PoP\Root\Component\AbstractComponent;
use GraphQLByPoP\GraphQLServer\Component as GraphQLServerComponent;
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
            \PoP\APIClients\Component::class,
            \PoP\APIEndpointsForWP\Component::class,
            \GraphQLByPoP\GraphQLServer\Component::class,
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
            parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
            ComponentConfiguration::setConfiguration($configuration);
            self::initYAMLServices(dirname(__DIR__));
            if (ComponentConfiguration::useGraphiQLExplorer()) {
                self::initYAMLServices(dirname(__DIR__), '/ConditionalOnEnvironment/UseGraphiQLExplorer/Overrides');
            }
        }
    }

    protected static function resolveEnabled()
    {
        return GraphQLServerComponent::isEnabled();
    }
}
