<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\UpdateCategoryTermMutationResolverTrait;

class UpdatePostCategoryTermMutationResolver extends AbstractMutatePostCategoryTermMutationResolver
{
    use UpdateCategoryTermMutationResolverTrait;
}
