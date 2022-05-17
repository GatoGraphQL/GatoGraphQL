<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\Root\Module\AbstractModule;
use PoP\Root\Environment;

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
    public function getDependedModuleClasses(): array
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
