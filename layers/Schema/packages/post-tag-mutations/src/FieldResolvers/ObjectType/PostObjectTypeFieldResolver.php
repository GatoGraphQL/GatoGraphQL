<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractCustomPostFieldResolver;

class PostFieldResolver extends AbstractCustomPostFieldResolver
{
    use SetTagsOnPostFieldResolverTrait;
}
