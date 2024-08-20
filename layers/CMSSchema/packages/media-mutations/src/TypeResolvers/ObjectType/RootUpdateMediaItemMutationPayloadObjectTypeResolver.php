<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class RootUpdateMediaItemMutationPayloadObjectTypeResolver extends AbstractMediaItemMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateMediaItemMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of updating the metadata for an attachment', 'media-mutations');
    }
}
