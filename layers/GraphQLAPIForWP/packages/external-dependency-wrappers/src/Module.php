<?php

declare(strict_types=1);

namespace GraphQLAPI\ExternalDependencyWrappers;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Engine\Module::class,
        ];
    }
}
