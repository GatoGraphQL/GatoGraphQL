<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractMyCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\Posts\TypeResolvers\InputObjectType\PostsFilterInputObjectTypeResolverInterface;

class RootMyPostsFilterInputObjectTypeResolver extends AbstractMyCustomPostsFilterInputObjectTypeResolver implements PostsFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootMyPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s posts', 'post-mutations');
    }
}
