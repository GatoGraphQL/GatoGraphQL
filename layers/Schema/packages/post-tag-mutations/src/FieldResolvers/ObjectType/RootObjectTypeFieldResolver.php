<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    use SetTagsOnPostObjectTypeFieldResolverTrait;

    protected function getSetTagsFieldName(): string
    {
        return 'setTagsOnPost';
    }
}
