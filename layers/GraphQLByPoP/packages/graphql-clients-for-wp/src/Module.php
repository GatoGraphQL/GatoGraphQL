<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP;

use PoP\Root\Module\AbstractComponent;

/**
 * Initialize component
 */
class Module extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPAPI\APIClients\Module::class,
            \PoPAPI\APIEndpointsForWP\Module::class,
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
