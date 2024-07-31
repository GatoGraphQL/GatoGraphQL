<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootDeleteCategoryTermInputObjectTypeResolver extends AbstractDeleteCategoryTermInputObjectTypeResolver implements DeleteCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteCategoryInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }
}
