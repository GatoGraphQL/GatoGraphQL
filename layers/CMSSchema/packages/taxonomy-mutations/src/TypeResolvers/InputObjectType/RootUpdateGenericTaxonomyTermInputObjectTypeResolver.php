<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class RootUpdateGenericTaxonomyTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTaxonomyTermInputObjectTypeResolver implements UpdateTaxonomyInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateTaxonomyTermInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}
