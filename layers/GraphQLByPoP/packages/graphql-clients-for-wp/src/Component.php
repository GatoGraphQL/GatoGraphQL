<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP;

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
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        /** @var ComponentConfiguration */
        $componentConfiguration = $this->getConfiguration();
        if ($componentConfiguration->useGraphiQLExplorer()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/UseGraphiQLExplorer/Overrides');
        }
    }
}
