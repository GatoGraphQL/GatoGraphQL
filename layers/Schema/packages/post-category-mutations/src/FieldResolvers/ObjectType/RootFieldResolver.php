<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractRootFieldResolver;

class RootFieldResolver extends AbstractRootFieldResolver
{
    use SetCategoriesOnPostFieldResolverTrait;

    protected function getSetCategoriesFieldName(): string
    {
        return 'setCategoriesOnPost';
    }
}
