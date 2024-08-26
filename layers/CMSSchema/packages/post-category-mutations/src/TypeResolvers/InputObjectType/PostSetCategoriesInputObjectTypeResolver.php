<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

class PostSetCategoriesInputObjectTypeResolver extends AbstractSetCategoriesOnPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostSetCategoriesInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
