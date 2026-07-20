<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

class RootDeleteMediaItemInputObjectTypeResolver extends AbstractDeleteMediaItemInputObjectTypeResolver
{
    protected function addIDInputField(): bool
    {
        return true;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteMediaItemInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete an attachment', 'gatographql');
    }
}
