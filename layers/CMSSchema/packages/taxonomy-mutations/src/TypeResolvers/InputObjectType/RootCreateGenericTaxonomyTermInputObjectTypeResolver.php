<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class RootCreateGenericTaxonomyTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTaxonomyTermInputObjectTypeResolver implements CreateTaxonomyInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateTaxonomyTermInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
