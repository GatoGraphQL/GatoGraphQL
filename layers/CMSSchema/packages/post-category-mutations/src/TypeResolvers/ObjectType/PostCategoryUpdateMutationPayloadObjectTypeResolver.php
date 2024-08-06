<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType;

class PostCategoryUpdateMutationPayloadObjectTypeResolver extends AbstractPostCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostCategoryUpdateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update nested mutation on a post category', 'category-mutations');
    }
}
