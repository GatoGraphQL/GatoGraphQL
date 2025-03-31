<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType;

class GenericTagAddMetaMutationPayloadObjectTypeResolver extends AbstractGenericTagMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericTagAddMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an add meta nested mutation on a tag term', 'tag-mutations');
    }
}
