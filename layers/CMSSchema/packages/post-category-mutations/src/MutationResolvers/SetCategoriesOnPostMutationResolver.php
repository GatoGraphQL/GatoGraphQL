<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\AbstractSetCategoriesOnCustomPostMutationResolver;

class SetCategoriesOnPostMutationResolver extends AbstractSetCategoriesOnCustomPostMutationResolver
{
    protected function getEntityName(): string
    {
        return $this->__('post', 'post-category-mutations');
    }
}
