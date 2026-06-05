<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

trait RootCreateTagTermInputObjectTypeResolverTrait
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create a tag term', 'gatographql');
    }

    protected function addIDInputField(): bool
    {
        return false;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return true;
    }
}
