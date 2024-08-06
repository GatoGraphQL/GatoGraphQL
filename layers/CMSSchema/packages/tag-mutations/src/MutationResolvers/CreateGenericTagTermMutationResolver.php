<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateTaxonomyTermMutationResolverTrait;

class CreateGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use CreateTaxonomyTermMutationResolverTrait;
}
