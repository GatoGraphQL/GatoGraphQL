<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\InputObjectType;

class RootPostsFilterInputObjectTypeResolver extends AbstractPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter posts', 'posts');
    }
}
