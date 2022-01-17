<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\TypeResolvers\InputObjectType;

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
