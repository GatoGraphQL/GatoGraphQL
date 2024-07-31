<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class CategoryTermDeleteInputObjectTypeResolver extends AbstractDeleteCategoryTermInputObjectTypeResolver implements DeleteCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryDeleteInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }
}
