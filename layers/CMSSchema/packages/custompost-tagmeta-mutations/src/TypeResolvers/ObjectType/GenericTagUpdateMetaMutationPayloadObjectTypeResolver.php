<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType;

class GenericTagUpdateMetaMutationPayloadObjectTypeResolver extends AbstractGenericTagMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericTagUpdateMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta nested mutation on a tag term', 'tag-mutations');
    }
}
