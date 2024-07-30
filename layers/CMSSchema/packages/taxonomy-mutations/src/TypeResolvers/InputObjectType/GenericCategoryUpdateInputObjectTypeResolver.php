<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class GenericTaxonomyUpdateInputObjectTypeResolver extends TaxonomyUpdateInputObjectTypeResolver implements UpdateGenericTaxonomyTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'GenericTaxonomyUpdateInput';
    }
}
