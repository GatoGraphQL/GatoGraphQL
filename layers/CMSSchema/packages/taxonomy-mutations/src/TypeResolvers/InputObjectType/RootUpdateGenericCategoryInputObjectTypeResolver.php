<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class RootUpdateGenericTaxonomyInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTaxonomyInputObjectTypeResolver implements UpdateTaxonomyInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateTaxonomyInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}
