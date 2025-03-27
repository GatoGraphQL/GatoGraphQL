<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\SetCategoryTermMetaMutationResolverTrait;

class SetGenericCategoryTermMetaMutationResolver extends AbstractMutateGenericCategoryTermMetaMutationResolver
{
    use SetCategoryTermMetaMutationResolverTrait;
}
