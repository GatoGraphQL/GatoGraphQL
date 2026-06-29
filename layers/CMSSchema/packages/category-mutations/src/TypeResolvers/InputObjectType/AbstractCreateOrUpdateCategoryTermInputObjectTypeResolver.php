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
        return $this->__('Input to create or update a category term', 'gatographql');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category to update', 'gatographql'),
            MutationInputProperties::NAME => $this->__('The name of the category', 'gatographql'),
            MutationInputProperties::DESCRIPTION => $this->__('The description of the category', 'gatographql'),
            MutationInputProperties::SLUG => $this->__('The slug of the category', 'gatographql'),
            MutationInputProperties::TAXONOMY => $this->__('The taxonomy of the category', 'gatographql'),
            MutationInputProperties::PARENT_BY => $this->__('The category\'s parent, or `null` to remove it', 'gatographql'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
