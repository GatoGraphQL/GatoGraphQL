<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ModuleProcessors;

use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;
use PoPSchema\Categories\ModuleProcessors\AbstractFieldDataloadModuleProcessor;

class PostCategoryFieldDataloadModuleProcessor extends AbstractFieldDataloadModuleProcessor
{
    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return PostCategoryTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }
}
