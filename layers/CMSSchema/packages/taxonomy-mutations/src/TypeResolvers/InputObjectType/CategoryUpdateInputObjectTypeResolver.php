<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class TaxonomyUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateTaxonomyInputObjectTypeResolver implements UpdateTaxonomyInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'TaxonomyUpdateInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}
