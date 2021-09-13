<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Categories\FieldResolvers\ObjectType\AbstractChildCategoryObjectTypeFieldResolver;
use PoPSchema\PostCategories\ModuleProcessors\PostCategoryFilterInputContainerModuleProcessor;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryTypeResolver;

class ChildPostCategoryObjectTypeFieldResolver extends AbstractChildCategoryObjectTypeFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryTypeResolver::class,
        ];
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'childCategories' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            'childCategoryCount' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT],
            'childCategoryNames' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            default => parent::getFieldDataFilteringModule($objectTypeResolver, $fieldName),
        };
    }
}
