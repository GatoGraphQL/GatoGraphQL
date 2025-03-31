<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType;

class PostCategorySetMetaMutationPayloadObjectTypeResolver extends AbstractPostMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostCategorySetMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta nested mutation on a post\'s category term', 'category-mutations');
    }
}
