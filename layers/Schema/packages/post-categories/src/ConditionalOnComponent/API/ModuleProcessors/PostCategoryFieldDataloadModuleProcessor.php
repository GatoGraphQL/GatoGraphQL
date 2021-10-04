<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Categories\ConditionalOnComponent\API\ModuleProcessors\AbstractFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PostCategoryFieldDataloadModuleProcessor extends AbstractFieldDataloadModuleProcessor
{
    protected PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver;

    #[Required]
    final public function autowirePostCategoryFieldDataloadModuleProcessor(
        PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver,
    ): void {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return $this->postCategoryObjectTypeResolver;
        }

        return parent::getRelationalTypeResolver($module);
    }
}
