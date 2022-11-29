<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType;

class CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver extends AbstractCustomPostMediaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostSetFeaturedImageMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting the featured image to a custom post (using nested mutations)', 'custompostmedia-mutations');
    }
}
