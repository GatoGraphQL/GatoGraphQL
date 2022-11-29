<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType;

class CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver extends AbstractCustomPostMediaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostRemoveFeaturedImageMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of removing the featured image from a custom post (using nested mutations)', 'custompostmedia-mutations');
    }
}
