<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\ObjectType;

class RootUpdateGenericTagTermMutationPayloadObjectTypeResolver extends AbstractGenericTagMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateGenericTagTermMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update mutation on a tag term', 'tag-mutations');
    }
}
