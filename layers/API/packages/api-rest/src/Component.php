<?php

declare(strict_types=1);

namespace PoP\RESTAPI;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\API\Component as APIComponent;

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
            \PoP\APIMirrorQuery\Component::class,
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
            self::initYAMLServices(dirname(__DIR__));
        }
    }

    protected static function resolveEnabled()
    {
        return APIComponent::isEnabled() && !Environment::disableRESTAPI();
    }
}
