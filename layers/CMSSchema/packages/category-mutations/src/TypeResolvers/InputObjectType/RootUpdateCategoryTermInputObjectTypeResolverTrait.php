<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

trait RootUpdateCategoryTermInputObjectTypeResolverTrait
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a category term', 'category-mutations');
    }

    protected function addIDInputField(): bool
    {
        return true;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return false;
    }
}
