<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

class MediaDeleteInputObjectTypeResolver extends AbstractDeleteMediaItemInputObjectTypeResolver
{
    protected function addIDInputField(): bool
    {
        return false;
    }

    public function getTypeName(): string
    {
        return 'MediaDeleteInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete an attachment (nested mutations)', 'gatographql');
    }
}
