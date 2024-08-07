<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

trait RootDeleteCategoryTermInputObjectTypeResolverTrait
{
    protected function addTaxonomyInputField(): bool
    {
        return true;
    }
}
