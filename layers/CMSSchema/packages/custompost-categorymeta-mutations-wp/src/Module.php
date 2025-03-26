<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutationsWP;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostCategoryMetaMutations\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CategoryMutationsWP\Module::class,
            \PoPCMSSchema\CategoryMetaMutationsWP\Module::class,
            \PoPCMSSchema\CustomPostCategoriesWP\Module::class,
            \PoPCMSSchema\CustomPostCategoryMetaMutations\Module::class,
            \PoPCMSSchema\CustomPostCategoryMutationsWP\Module::class,
            \PoPCMSSchema\TaxonomyMetaMutationsWP\Module::class,
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
    }
}
