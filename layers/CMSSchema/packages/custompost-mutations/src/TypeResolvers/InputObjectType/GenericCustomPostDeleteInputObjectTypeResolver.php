<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class GenericCustomPostDeleteInputObjectTypeResolver extends AbstractDeleteCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostDeleteInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a custom post', 'gatographql');
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}
