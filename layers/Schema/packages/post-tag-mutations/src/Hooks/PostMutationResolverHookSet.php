<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Hooks;

use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\Facades\PostTagTypeMutationAPIFacade;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    protected function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }

    protected function getCustomPostType(): string
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPostCustomPostType();
    }

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return PostTagTypeMutationAPIFacade::getInstance();
    }
}
