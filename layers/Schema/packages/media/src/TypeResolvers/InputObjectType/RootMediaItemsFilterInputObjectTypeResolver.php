<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeResolvers\InputObjectType;

class RootMediaItemsFilterInputObjectTypeResolver extends AbstractMediaItemsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMediaItemsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter media items', 'media');
    }
}