<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

class GenericCustomPostDeleteMutationPayloadObjectTypeResolver extends AbstractGenericCustomPostMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostDeleteMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a custom post', 'gatographql');
    }
}
