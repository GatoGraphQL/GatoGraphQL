<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateTaxonomyTermMutationResolverTrait;

class CreateGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use CreateTaxonomyTermMutationResolverTrait;
}
