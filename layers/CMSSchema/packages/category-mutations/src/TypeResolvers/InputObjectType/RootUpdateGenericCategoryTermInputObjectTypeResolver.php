<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootUpdateGenericCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateGenericCategoryInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return false;
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
