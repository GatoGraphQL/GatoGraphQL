<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\AbstractMutateTaxonomyTermMutationResolver;

abstract class AbstractMutateGenericTaxonomyTermMutationResolver extends AbstractMutateTaxonomyTermMutationResolver
{
    public function getTaxonomyName(): string
    {
        return '';
    }
}
