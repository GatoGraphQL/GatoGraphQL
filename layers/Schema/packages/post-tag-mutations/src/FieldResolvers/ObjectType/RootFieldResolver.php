<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractRootFieldResolver;

class RootFieldResolver extends AbstractRootFieldResolver
{
    use SetTagsOnPostFieldResolverTrait;

    protected function getSetTagsFieldName(): string
    {
        return 'setTagsOnPost';
    }
}
