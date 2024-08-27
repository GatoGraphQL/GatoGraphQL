<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

trait RootUpdateTagTermInputObjectTypeResolverTrait
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a tag term', 'tag-mutations');
    }

    protected function addIDInputField(): bool
    {
        return true;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return false;
    }
}
