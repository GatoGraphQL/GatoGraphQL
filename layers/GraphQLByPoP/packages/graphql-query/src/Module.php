<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery;

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
            \PoPAPI\GraphQLAPI\Module::class,
        ];
    }
}
