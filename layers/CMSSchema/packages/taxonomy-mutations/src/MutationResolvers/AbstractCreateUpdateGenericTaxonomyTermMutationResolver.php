<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\AbstractCreateOrUpdateTaxonomyTermMutationResolver;

abstract class AbstractCreateUpdateGenericTaxonomyTermMutationResolver extends AbstractCreateOrUpdateTaxonomyTermMutationResolver
{
    public function getTaxonomyName(): string
    {
        return '';
    }
}
