<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Categories\ConditionalOnComponent\API\ModuleProcessors\AbstractFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;

class PostCategoryFieldDataloadModuleProcessor extends AbstractFieldDataloadModuleProcessor
{
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;

    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        return $this->postCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return $this->getPostCategoryObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
