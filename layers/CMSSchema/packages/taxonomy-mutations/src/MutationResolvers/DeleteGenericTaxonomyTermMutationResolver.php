<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\DeleteTaxonomyTermMutationResolverTrait;

class DeleteGenericTaxonomyTermMutationResolver extends AbstractMutateGenericTaxonomyTermMutationResolver
{
    use DeleteTaxonomyTermMutationResolverTrait;
}
