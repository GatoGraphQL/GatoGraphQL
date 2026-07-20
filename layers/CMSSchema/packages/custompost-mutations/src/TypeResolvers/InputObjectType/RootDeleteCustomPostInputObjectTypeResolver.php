<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootDeleteCustomPostInputObjectTypeResolver extends AbstractDeleteCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteCustomPostInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a custom post', 'gatographql');
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
