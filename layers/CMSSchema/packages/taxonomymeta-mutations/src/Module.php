<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations;

use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;

class Module extends AbstractModule
{
    protected function requiresSatisfyingModule(): bool
    {
        return true;
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\TaxonomyMeta\Module::class,
            \PoPCMSSchema\TaxonomyMutations\Module::class,
        ];
    }
}
