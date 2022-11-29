<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType;

class RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver extends AbstractCustomPostMediaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootRemoveFeaturedImageFromCustomPostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of removing the featured image from a custom post', 'custompostmedia-mutations');
    }
}
