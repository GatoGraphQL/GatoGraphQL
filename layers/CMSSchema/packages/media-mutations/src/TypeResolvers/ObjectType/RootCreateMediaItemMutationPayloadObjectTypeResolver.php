<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class RootCreateMediaItemMutationPayloadObjectTypeResolver extends AbstractMediaItemMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateMediaItemMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of uploading an attachment', 'media-mutations');
    }
}
