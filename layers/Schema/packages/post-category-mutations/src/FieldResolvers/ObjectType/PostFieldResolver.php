<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractCustomPostFieldResolver;

class PostFieldResolver extends AbstractCustomPostFieldResolver
{
    use SetCategoriesOnPostFieldResolverTrait;
}
