<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers;

use PoPSchema\CustomPostTagMutations\FieldResolvers\AbstractCustomPostFieldResolver;

class PostFieldResolver extends AbstractCustomPostFieldResolver
{
    use SetTagsOnPostFieldResolverTrait;
}
