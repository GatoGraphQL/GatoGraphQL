<?php

declare(strict_types=1);

namespace PoPSchema\PostsWP\ConditionalOnContext\AddPostTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Posts\ConditionalOnContext\AddPostTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\PostCustomPostTypeResolverPicker as UpstreamPostCustomPostTypeResolverPicker;

class PostCustomPostTypeResolverPicker extends UpstreamPostCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPostCustomPostType();
    }
}
