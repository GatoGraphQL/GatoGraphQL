<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootUpdateCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateCategoryInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a category term', 'category-mutations');
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return false;
    }
}
