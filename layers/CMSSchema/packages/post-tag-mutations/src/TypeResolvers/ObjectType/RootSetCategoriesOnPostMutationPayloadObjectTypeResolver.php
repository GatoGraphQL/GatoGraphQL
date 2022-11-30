<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType;

class RootSetTagsOnPostMutationPayloadObjectTypeResolver extends AbstractPostTagsMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetTagsOnPostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting tags on a post', 'postcategory-mutations');
    }
}
