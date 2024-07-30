<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\AbstractCreateOrUpdateCategoryMutationResolver;

abstract class AbstractCreateUpdateGenericCategoryMutationResolver extends AbstractCreateOrUpdateCategoryMutationResolver
{
    public function getTaxonomyName(): string
    {
        return '';
    }
}
