<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType;

class PostCategoryUpdateMetaMutationPayloadObjectTypeResolver extends AbstractPostCategoryMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostCategoryUpdateMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta nested mutation on a post\'s category term', 'category-mutations');
    }
}
