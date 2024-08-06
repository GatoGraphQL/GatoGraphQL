<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateTaxonomyTermMutationResolverTrait;

class CreatePostTagTermMutationResolver extends AbstractMutatePostTagTermMutationResolver
{
    use CreateTaxonomyTermMutationResolverTrait;
}
