<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CategoryByOneofInputObjectTypeResolver;
use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateTaxonomyTermInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateTaxonomyTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface, CreateCategoryTermInputObjectTypeResolverInterface
{
    private ?CategoryByOneofInputObjectTypeResolver $categoryByOneofInputObjectTypeResolver = null;

    final public function setCategoryByOneofInputObjectTypeResolver(CategoryByOneofInputObjectTypeResolver $categoryByOneofInputObjectTypeResolver): void
    {
        $this->categoryByOneofInputObjectTypeResolver = $categoryByOneofInputObjectTypeResolver;
    }
    final protected function getCategoryByOneofInputObjectTypeResolver(): CategoryByOneofInputObjectTypeResolver
    {
        if ($this->categoryByOneofInputObjectTypeResolver === null) {
            /** @var CategoryByOneofInputObjectTypeResolver */
            $categoryByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryByOneofInputObjectTypeResolver::class);
            $this->categoryByOneofInputObjectTypeResolver = $categoryByOneofInputObjectTypeResolver;
        }
        return $this->categoryByOneofInputObjectTypeResolver;
    }

    protected function getTaxonomyTermParentInputObjectTypeResolver(): InputTypeResolverInterface
    {
        return $this->getCategoryByOneofInputObjectTypeResolver();
    }
    
    protected function addParentIDInputField(): bool
    {
        return true;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create or update a category term', 'category-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category to update', 'category-mutations'),
            MutationInputProperties::NAME => $this->__('The name of the category', 'category-mutations'),
            MutationInputProperties::DESCRIPTION => $this->__('The description of the category', 'category-mutations'),
            MutationInputProperties::SLUG => $this->__('The slug of the category', 'category-mutations'),
            MutationInputProperties::PARENT_BY => $this->__('The category\'s parent', 'category-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
