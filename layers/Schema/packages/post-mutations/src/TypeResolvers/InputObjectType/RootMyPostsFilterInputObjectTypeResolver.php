<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\TypeResolvers\InputObjectType;

class RootMyPostsFilterInputObjectTypeResolver extends AbstractMyPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter the logged-in user\'s posts', 'post-mutations');
    }
}
