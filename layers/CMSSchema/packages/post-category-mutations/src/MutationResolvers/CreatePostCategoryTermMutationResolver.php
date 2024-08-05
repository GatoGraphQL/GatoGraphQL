<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateTaxonomyTermMutationResolverTrait;

class CreatePostCategoryTermMutationResolver extends AbstractMutatePostCategoryTermMutationResolver
{
    use CreateTaxonomyTermMutationResolverTrait;
}
