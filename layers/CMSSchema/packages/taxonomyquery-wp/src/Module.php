<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyQueryWP;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\TaxonomyQuery\Module::class,
            \PoPCMSSchema\SchemaCommonsWP\Module::class,
        ];
    }
}
