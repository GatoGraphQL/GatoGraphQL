<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateTaxonomyTermMutationResolverTrait;

class CreateGenericCategoryTermMutationResolver extends AbstractMutateGenericCategoryTermMutationResolver
{
    use CreateTaxonomyTermMutationResolverTrait;
}
