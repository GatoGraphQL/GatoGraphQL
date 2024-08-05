<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\DeleteCategoryTermMutationResolverTrait;

class DeletePostCategoryTermMutationResolver extends AbstractMutatePostCategoryTermMutationResolver
{
    use DeleteCategoryTermMutationResolverTrait;
}
