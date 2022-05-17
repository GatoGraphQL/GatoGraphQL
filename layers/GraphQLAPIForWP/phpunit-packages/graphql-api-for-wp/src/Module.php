<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI;

use PoP\Root\Module\AbstractModule;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PHPUnitForGraphQLAPI\WPFakerSchema\Module::class,
            \PHPUnitForGraphQLAPI\WebserverRequests\Module::class,
        ];
    }
}
