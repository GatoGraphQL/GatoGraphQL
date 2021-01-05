<?php

declare(strict_types=1);

namespace PoP\RESTAPI;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\RESTAPI\Config\ServiceConfiguration;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\API\Component as APIComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    use CanDisableComponentTrait;

    // const VERSION = '0.1.0';

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
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if (self::isEnabled()) {
            parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
            self::initYAMLServices(dirname(__DIR__));
            ServiceConfiguration::initialize();
        }
    }

    protected static function resolveEnabled()
    {
        return APIComponent::isEnabled() && !Environment::disableRESTAPI();
    }
}
