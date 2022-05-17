<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema;

use PoP\Root\Module\AbstractModule;
use PoP\Root\Environment;

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

    protected function resolveEnabled(): bool
    {
        return Environment::isApplicationEnvironmentDevPHPUnit();
    }
}
