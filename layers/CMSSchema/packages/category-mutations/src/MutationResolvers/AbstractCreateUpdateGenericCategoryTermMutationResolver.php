<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\AbstractCreateOrUpdateCategoryTermMutationResolver;

abstract class AbstractCreateUpdateGenericCategoryTermMutationResolver extends AbstractCreateOrUpdateCategoryTermMutationResolver
{
    public function getTaxonomyName(): string
    {
        return '';
    }
}
