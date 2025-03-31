<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType;

class GenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver extends AbstractGenericCustomPostMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostUpdateMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta nested mutation on a custom post', 'custompost-mutations');
    }
}
