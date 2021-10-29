<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\MutationResolvers;

use PoPSchema\CustomPostCategoryMutations\MutationResolvers\AbstractSetCategoriesOnCustomPostMutationResolver;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\PostCategoryMutations\Facades\PostCategoryTypeMutationAPIFacade;

class SetCategoriesOnPostMutationResolver extends AbstractSetCategoriesOnCustomPostMutationResolver
{
    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return PostCategoryTypeMutationAPIFacade::getInstance();
    }

    protected function getEntityName(): string
    {
        return $this->translationAPI->__('post', 'post-category-mutations');
    }
}
