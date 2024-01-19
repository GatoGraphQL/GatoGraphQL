<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Media\TypeResolvers\InputObjectType\AbstractMediaItemsFilterInputObjectTypeResolver;

class RootMyMediaItemsFilterInputObjectTypeResolver extends AbstractMediaItemsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyMediaItemsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s media items', 'media-mutations');
    }
}
