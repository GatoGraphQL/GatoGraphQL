<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Categories\Module::class,
            \PoPCMSSchema\CategoryMetaMutations\Module::class,
            \PoPCMSSchema\CustomPostCategoryMutations\Module::class,
            \PoPCMSSchema\TaxonomyMetaMutations\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }
}
