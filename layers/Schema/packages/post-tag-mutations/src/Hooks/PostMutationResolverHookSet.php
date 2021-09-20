<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Hooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        CustomPostTypeAPIInterface $customPostTypeAPI,
        protected PostObjectTypeResolver $postObjectTypeResolver,
        protected PostTypeAPIInterface $postTypeAPI,
        protected PostTagTypeMutationAPIInterface $postTagTypeMutationAPI,
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

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return $this->postTagTypeMutationAPI;
    }
}
