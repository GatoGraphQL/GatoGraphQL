<?php

declare(strict_types=1);

namespace PoP\AccessControl;

use PoP\Root\Component\AbstractComponent;
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
    public function getDependedComponentClasses(): array
    {
        return [
            \PoP\MandatoryDirectivesByConfiguration\Component::class,
            \PoP\Engine\Component::class,
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
        if ($this->isEnabled()) {
            ComponentConfiguration::setConfiguration($configuration);
            $this->initServices(dirname(__DIR__));
            $this->initSchemaServices(dirname(__DIR__), $skipSchema);
        }
    }

    protected function resolveEnabled(): bool
    {
        return !Environment::disableAccessControl();
    }
}
