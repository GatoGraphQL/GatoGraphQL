<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class RootCreateGenericTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTagTermInputObjectTypeResolver implements CreateTagTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateGenericTagInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }

    protected function isNameInputFieldMandatory(): bool
    {
        return true;
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return true;
    }
}
