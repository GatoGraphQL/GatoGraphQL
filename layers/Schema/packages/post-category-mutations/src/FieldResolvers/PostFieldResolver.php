<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers;

use PoPSchema\CustomPostCategoryMutations\FieldResolvers\AbstractCustomPostFieldResolver;

class PostFieldResolver extends AbstractCustomPostFieldResolver
{
    use SetCategoriesOnPostFieldResolverTrait;
}
