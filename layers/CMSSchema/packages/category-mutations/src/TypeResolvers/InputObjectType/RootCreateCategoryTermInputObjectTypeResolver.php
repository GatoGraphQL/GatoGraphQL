<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootCreateCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements CreateCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateCategoryInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create a category term', 'category-mutations');
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return true;
    }
}
