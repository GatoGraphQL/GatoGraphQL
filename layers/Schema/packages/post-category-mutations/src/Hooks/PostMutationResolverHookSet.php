<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\Hooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\PostCategoryMutations\Facades\PostCategoryTypeMutationAPIFacade;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        CustomPostTypeAPIInterface $customPostTypeAPI,
        protected PostObjectTypeResolver $postObjectTypeResolver,
        protected PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver,
        protected PostTypeAPIInterface $postTypeAPI,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $customPostTypeAPI,
        );
    }

    protected function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->postObjectTypeResolver;
    }

    protected function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }

    protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->postCategoryObjectTypeResolver;
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return PostCategoryTypeMutationAPIFacade::getInstance();
    }
}
