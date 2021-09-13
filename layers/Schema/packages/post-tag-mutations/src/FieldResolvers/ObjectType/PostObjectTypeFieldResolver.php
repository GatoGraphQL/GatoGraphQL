<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    use SetTagsOnPostObjectTypeFieldResolverTrait;
}
