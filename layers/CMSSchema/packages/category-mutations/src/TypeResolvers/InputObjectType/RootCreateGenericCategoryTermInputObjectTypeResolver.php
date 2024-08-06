<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootCreateGenericCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermInputObjectTypeResolver implements CreateCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateGenericCategoryInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return true;
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return true;
    }
}
