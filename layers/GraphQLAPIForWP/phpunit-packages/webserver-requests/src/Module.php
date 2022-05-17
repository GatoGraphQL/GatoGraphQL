<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\Root\Module\AbstractComponent;
use PoP\Root\Environment;

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
            \GraphQLByPoP\GraphQLServer\Module::class,
        ];
    }

    protected function resolveEnabled(): bool
    {
        return Environment::isApplicationEnvironmentDevPHPUnit();
    }
}
