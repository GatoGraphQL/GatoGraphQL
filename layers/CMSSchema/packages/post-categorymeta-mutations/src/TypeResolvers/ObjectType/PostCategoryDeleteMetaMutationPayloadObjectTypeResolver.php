<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType;

class PostCategoryDeleteMetaMutationPayloadObjectTypeResolver extends AbstractPostCategoryMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostCategoryDeleteMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete meta nested mutation on a post\'s category term', 'category-mutations');
    }
}
