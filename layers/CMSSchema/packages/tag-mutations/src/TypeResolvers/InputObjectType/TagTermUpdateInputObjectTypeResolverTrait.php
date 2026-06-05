<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

trait TagTermUpdateInputObjectTypeResolverTrait
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a tag term', 'gatographql');
    }

    protected function addIDInputField(): bool
    {
        return false;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return false;
    }
}
