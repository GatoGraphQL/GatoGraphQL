<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType;

class RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver extends AbstractCustomPostMediaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetFeaturedImageOnCustomPostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting the featured image to a custom post', 'custompostmedia-mutations');
    }
}
