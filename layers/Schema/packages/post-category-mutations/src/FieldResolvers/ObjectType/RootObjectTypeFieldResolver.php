<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    use SetCategoriesOnPostObjectTypeFieldResolverTrait;

    protected function getSetCategoriesFieldName(): string
    {
        return 'setCategoriesOnPost';
    }
}
