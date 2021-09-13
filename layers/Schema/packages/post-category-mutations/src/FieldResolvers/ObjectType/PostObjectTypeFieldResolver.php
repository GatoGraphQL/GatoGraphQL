<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    use SetCategoriesOnPostObjectTypeFieldResolverTrait;
}
