<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractDeleteCustomPostInputObjectTypeResolver;

class RootDeletePageInputObjectTypeResolver extends AbstractDeleteCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeletePageInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a page', 'gatographql');
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}
