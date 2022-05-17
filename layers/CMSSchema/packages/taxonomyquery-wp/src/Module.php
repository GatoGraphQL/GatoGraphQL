<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyQueryWP;

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
            \PoPCMSSchema\TaxonomyQuery\Module::class,
            \PoPCMSSchema\SchemaCommonsWP\Module::class,
        ];
    }
}
