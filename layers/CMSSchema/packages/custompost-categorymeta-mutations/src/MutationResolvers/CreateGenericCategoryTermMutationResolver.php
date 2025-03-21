<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateTaxonomyTermMutationResolverTrait;

class CreateGenericCategoryTermMutationResolver extends AbstractMutateGenericCategoryTermMutationResolver
{
    use CreateTaxonomyTermMutationResolverTrait;
}
