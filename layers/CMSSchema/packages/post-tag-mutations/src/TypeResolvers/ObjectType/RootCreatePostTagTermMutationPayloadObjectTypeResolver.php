<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType;

class RootCreatePostTagTermMutationPayloadObjectTypeResolver extends AbstractPostTagMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreatePostTagTermMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a post tag term', 'tag-mutations');
    }
}
