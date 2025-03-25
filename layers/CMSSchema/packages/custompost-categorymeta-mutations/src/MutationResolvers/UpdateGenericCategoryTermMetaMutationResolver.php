<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\UpdateCategoryTermMetaMutationResolverTrait;

class UpdateGenericCategoryTermMetaMutationResolver extends AbstractMutateGenericCategoryTermMetaMutationResolver
{
    use UpdateCategoryTermMetaMutationResolverTrait;
}
