<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class MediaUpdateMutationPayloadObjectTypeResolver extends AbstractMediaItemMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'MediaUpdateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of updating the metadata for an attachment (nested mutations)', 'media-mutations');
    }
}
