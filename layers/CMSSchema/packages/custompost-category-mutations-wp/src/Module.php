<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutationsWP;

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
            \PoPCMSSchema\PostCategoryMutations\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CategoryMutationsWP\Module::class,
            \PoPCMSSchema\PostCategoryMutations\Module::class,
            \PoPCMSSchema\CustomPostMutationsWP\Module::class,
            \PoPCMSSchema\PostCategoriesWP\Module::class,
            \PoPCMSSchema\UserStateMutationsWP\Module::class,
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
