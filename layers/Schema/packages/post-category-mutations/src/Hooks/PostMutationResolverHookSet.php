<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\Hooks;

use PoPSchema\CustomPostCategoryMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostCategoryMutations\Facades\PostCategoryTypeMutationAPIFacade;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    protected function getCustomPostTypeResolverClass(): string
    {
        return PostObjectTypeResolver::class;
    }

    protected function getCustomPostType(): string
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPostCustomPostType();
    }

    protected function getCategoryTypeResolverClass(): string
    {
        return PostCategoryObjectTypeResolver::class;
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return PostCategoryTypeMutationAPIFacade::getInstance();
    }
}
