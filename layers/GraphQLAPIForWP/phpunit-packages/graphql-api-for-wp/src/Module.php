<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PHPUnitForGraphQLAPI\WPFakerSchema\Module::class,
            \PHPUnitForGraphQLAPI\WebserverRequests\Module::class,
        ];
    }
}
