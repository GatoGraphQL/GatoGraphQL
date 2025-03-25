<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\DeleteCategoryTermMetaMutationResolverTrait;

class DeleteGenericCategoryTermMetaMutationResolver extends AbstractMutateGenericCategoryTermMetaMutationResolver
{
    use DeleteCategoryTermMetaMutationResolverTrait;
}
