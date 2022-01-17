<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

class RootMediaItemsFilterInputObjectTypeResolver extends AbstractMediaItemsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMediaItemsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter media items', 'media');
    }
}
