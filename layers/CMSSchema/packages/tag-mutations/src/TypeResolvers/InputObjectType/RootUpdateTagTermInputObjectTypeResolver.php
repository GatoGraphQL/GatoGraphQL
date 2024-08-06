<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class RootUpdateTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateTagTermInputObjectTypeResolver implements UpdateTagTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateTagInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a tag term', 'tag-mutations');
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return false;
    }
}
