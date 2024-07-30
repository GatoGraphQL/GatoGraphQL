<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\CreateCategoryMutationResolverTrait;

class CreateGenericCategoryMutationResolver extends AbstractCreateUpdateGenericCategoryMutationResolver
{
    use CreateCategoryMutationResolverTrait;
}
