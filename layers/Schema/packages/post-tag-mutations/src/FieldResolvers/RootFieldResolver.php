<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers;

use PoPSchema\CustomPostTagMutations\FieldResolvers\AbstractRootFieldResolver;

class RootFieldResolver extends AbstractRootFieldResolver
{
    use SetTagsOnPostFieldResolverTrait;

    protected function getSetTagsFieldName(): string
    {
        return 'setTagsOnPost';
    }
}
