<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class MediaDeleteMutationPayloadObjectTypeResolver extends AbstractMediaItemMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'MediaDeleteMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of deleting an attachment (nested mutations)', 'gatographql');
    }
}
