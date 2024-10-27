<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateTaxonomyTermInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateTaxonomyTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface, CreateCategoryTermInputObjectTypeResolverInterface
{
    private ?CategoryByOneofInputObjectTypeResolver $parentCategoryByOneofInputObjectTypeResolver = null;

    final protected function getCategoryByOneofInputObjectTypeResolver(): CategoryByOneofInputObjectTypeResolver
    {
        if ($this->parentCategoryByOneofInputObjectTypeResolver === null) {
            /** @var CategoryByOneofInputObjectTypeResolver */
            $parentCategoryByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryByOneofInputObjectTypeResolver::class);
            $this->parentCategoryByOneofInputObjectTypeResolver = $parentCategoryByOneofInputObjectTypeResolver;
        }
        return $this->parentCategoryByOneofInputObjectTypeResolver;
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
            MutationInputProperties::TAXONOMY => $this->__('The taxonomy of the category', 'category-mutations'),
            MutationInputProperties::PARENT_BY => $this->__('The category\'s parent, or `null` to remove it', 'category-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
