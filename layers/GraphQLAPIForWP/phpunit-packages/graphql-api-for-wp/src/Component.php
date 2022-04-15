<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI;

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
    public function getDependedComponentClasses(): array
    {
        return [
            \PHPUnitForGraphQLAPI\WPFakerSchema\Component::class,
            \PHPUnitForGraphQLAPI\WebserverRequests\Component::class,
        ];
    }
}
