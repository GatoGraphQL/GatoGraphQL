<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

class RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver extends AbstractGenericCustomPostMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateCustomPostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update mutation on a custom post', 'custompost-mutations');
    }
}
