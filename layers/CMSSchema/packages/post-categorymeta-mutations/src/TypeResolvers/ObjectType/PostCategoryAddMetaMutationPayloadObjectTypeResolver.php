<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType;

class PostCategoryAddMetaMutationPayloadObjectTypeResolver extends AbstractPostCategoryMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostCategoryAddMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an add meta nested mutation on a post\'s category term', 'category-mutations');
    }
}
