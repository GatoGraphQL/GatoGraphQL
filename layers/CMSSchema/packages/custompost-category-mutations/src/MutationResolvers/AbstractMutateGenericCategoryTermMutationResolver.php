<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\AbstractMutateCategoryTermMutationResolver;

abstract class AbstractMutateGenericCategoryTermMutationResolver extends AbstractMutateCategoryTermMutationResolver
{
    public function getTaxonomyName(): string
    {
        return '';
    }
}
