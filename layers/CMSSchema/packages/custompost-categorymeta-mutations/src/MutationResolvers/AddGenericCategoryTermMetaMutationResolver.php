<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\AddCategoryTermMetaMutationResolverTrait;

class AddGenericCategoryTermMetaMutationResolver extends AbstractMutateGenericCategoryTermMetaMutationResolver
{
    use AddCategoryTermMetaMutationResolverTrait;
}
