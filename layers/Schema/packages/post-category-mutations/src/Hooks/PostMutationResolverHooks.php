<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\Hooks;

use PoPSchema\CustomPostCategoryMutations\Hooks\AbstractCustomPostMutationResolverHooks;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\PostCategoryMutations\Facades\PostCategoryTypeMutationAPIFacade;

class PostMutationResolverHooks extends AbstractCustomPostMutationResolverHooks
{
    protected function getTypeResolverClass(): string
    {
        return PostTypeResolver::class;
    }

    protected function getCustomPostType(): string
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPostCustomPostType();
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return PostCategoryTypeMutationAPIFacade::getInstance();
    }
}
