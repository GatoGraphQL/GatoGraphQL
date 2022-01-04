<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP;

use PoP\Root\Managers\ComponentManager;
use GraphQLByPoP\GraphQLServer\Component as GraphQLServerComponent;
use PoP\BasicService\Component\AbstractComponent;
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
            \PoP\APIClients\Component::class,
            \PoP\APIEndpointsForWP\Component::class,
            \GraphQLByPoP\GraphQLServer\Component::class,
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
        if ($this->isEnabled()) {
            $this->initServices(dirname(__DIR__));
            /** @var ComponentConfiguration */
            $componentConfiguration = $this->getConfiguration();
            if ($componentConfiguration->useGraphiQLExplorer()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnContext/UseGraphiQLExplorer/Overrides');
            }
        }
    }

    protected function resolveEnabled(): bool
    {
        return ComponentManager::getComponent(GraphQLServerComponent::class)->isEnabled();
    }
}
