<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType;

class RootDeletePostCategoryTermMutationPayloadObjectTypeResolver extends AbstractPostCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeletePostCategoryTermMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a post category term', 'category-mutations');
    }
}
