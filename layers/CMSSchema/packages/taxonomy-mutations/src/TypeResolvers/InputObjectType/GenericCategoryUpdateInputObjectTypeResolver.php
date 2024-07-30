<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class GenericTaxonomyUpdateInputObjectTypeResolver extends TaxonomyUpdateInputObjectTypeResolver implements UpdateGenericTaxonomyInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'GenericTaxonomyUpdateInput';
    }
}
