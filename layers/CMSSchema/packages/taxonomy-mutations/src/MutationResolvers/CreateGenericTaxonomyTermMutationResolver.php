<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateTaxonomyTermMutationResolverTrait;

class CreateGenericTaxonomyTermMutationResolver extends AbstractCreateUpdateGenericTaxonomyTermMutationResolver
{
    use CreateTaxonomyTermMutationResolverTrait;
}
