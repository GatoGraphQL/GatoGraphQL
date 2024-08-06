<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\ObjectType;

class RootDeleteGenericTagTermMutationPayloadObjectTypeResolver extends AbstractGenericTagMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteGenericTagTermMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a tag term', 'tag-mutations');
    }
}
