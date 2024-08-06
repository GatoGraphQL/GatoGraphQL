<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class RootUpdateGenericTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTagTermInputObjectTypeResolver implements UpdateTagTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateGenericTagInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return false;
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
