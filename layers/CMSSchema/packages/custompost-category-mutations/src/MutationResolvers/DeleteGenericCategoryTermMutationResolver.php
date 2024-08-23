<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\DeleteCategoryTermMutationResolverTrait;

class DeleteGenericCategoryTermMutationResolver extends AbstractMutateGenericCategoryTermMutationResolver
{
    use DeleteCategoryTermMutationResolverTrait;
}
