<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\BasicService\Component\AbstractComponent;

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
            \PoP\LooseContracts\Component::class,
            \PoP\Routing\Component::class,
            \PoP\ModuleRouting\Component::class,
            \PoP\ComponentModel\Component::class,
            \PoP\CacheControl\Component::class,
            \PoP\GuzzleHelpers\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        ComponentConfiguration::setConfiguration($configuration);
        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
        if (!Environment::disableGuzzleOperators()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/Guzzle');
        }
    }
}
