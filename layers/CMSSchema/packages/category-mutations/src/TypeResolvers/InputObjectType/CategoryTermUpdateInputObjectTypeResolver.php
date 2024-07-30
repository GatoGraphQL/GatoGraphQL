<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class CategoryTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements UpdateCategoryInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryUpdateInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }
}
