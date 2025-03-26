<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers\AddTaxonomyTermMetaMutationResolverTrait;

class AddGenericCategoryTermMetaMutationResolver extends AbstractMutateGenericCategoryTermMetaMutationResolver
{
    use AddTaxonomyTermMetaMutationResolverTrait;
}
