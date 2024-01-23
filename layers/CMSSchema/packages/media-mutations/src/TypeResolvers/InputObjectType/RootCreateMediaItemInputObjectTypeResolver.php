<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

class RootCreateMediaItemInputObjectTypeResolver extends AbstractCreateMediaItemInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateMediaItemInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to upload an attachment', 'media-mutations');
    }
}
