<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\EnumType;

use PoP\ComponentModel\App;
use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoPCMSSchema\Categories\ModuleConfiguration as CategoriesModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class CategoryEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CategoryEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        /** @var CategoriesModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoriesModule::class)->getConfiguration();
        return $moduleConfiguration->getQueryableCategoryTaxonomies();
    }
}
