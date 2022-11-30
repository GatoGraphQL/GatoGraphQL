<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType;

class PostSetTagsMutationPayloadObjectTypeResolver extends AbstractPostTagsMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostSetTagsMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting tags on a post (using nested mutations)', 'postcategory-mutations');
    }
}
