<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers;

use PoPSchema\CustomPostCategoryMutations\FieldResolvers\AbstractRootFieldResolver;

class RootFieldResolver extends AbstractRootFieldResolver
{
    use SetCategoriesOnPostFieldResolverTrait;

    protected function getSetCategoriesFieldName(): string
    {
        return 'setCategoriesOnPost';
    }
}
