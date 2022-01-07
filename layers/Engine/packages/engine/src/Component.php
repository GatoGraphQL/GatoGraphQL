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
            \PoP\LooseContracts\Component::class,
            \PoP\Routing\Component::class,
            \PoP\ModuleRouting\Component::class,
            \PoP\ComponentModel\Component::class,
            \PoP\CacheControl\Component::class,
            \PoP\GuzzleHelpers\Component::class,
            \PoP\FunctionFields\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
        if (!Environment::disableGuzzleOperators()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/Guzzle');
        }
    }
}
