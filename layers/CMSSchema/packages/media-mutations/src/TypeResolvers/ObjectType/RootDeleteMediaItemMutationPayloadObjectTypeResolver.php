<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class RootDeleteMediaItemMutationPayloadObjectTypeResolver extends AbstractMediaItemMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteMediaItemMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of deleting an attachment', 'gatographql');
    }
}
