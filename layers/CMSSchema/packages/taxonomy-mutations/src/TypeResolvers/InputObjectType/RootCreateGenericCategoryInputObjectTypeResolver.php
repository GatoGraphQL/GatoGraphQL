<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class RootCreateGenericTaxonomyInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTaxonomyInputObjectTypeResolver implements CreateTaxonomyInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateTaxonomyInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
