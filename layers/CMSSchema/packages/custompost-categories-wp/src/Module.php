<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoriesWP;

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
            \PoPCMSSchema\CustomPostsWP\Module::class,
            \PoPCMSSchema\CategoriesWP\Module::class,
        ];
    }
}
