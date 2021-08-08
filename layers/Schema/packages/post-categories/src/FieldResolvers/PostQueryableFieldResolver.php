<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Categories\FieldResolvers\AbstractCustomPostQueryableFieldResolver;
use PoPSchema\Categories\ModuleProcessors\FilterInputContainerModuleProcessor;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;

class PostQueryableFieldResolver extends AbstractCustomPostQueryableFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'categories' => $this->translationAPI->__('Categories added to this post', 'post-categories'),
            'categoryCount' => $this->translationAPI->__('Number of categories added to this post', 'post-categories'),
            'categoryNames' => $this->translationAPI->__('Names of the categories added to this post', 'post-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'categories' => [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_CATEGORIES],
            'categoryCount' => [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_CATEGORYCOUNT],
            'categoryNames' => [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_CATEGORIES],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        };
    }
}
