<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\CreateCategoryTermMutationResolverTrait;

class CreateGenericCategoryTermMutationResolver extends AbstractCreateUpdateGenericCategoryTermMutationResolver
{
    use CreateCategoryTermMutationResolverTrait;
}
