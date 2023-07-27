<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

class CustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver extends AbstractFilterCustomPostsByCategoriesInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'FilterCustomPostsByCategoriesInput';
    }

    protected function addCategoryTaxonomyFilterInput(): bool
    {
        return true;
    }
}
