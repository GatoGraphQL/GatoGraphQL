<?php

declare(strict_types=1);

namespace PoPSchema\PostsWP\ConditionalOnEnvironment\AddPostTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Posts\ConditionalOnEnvironment\AddPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\PostCustomPostTypeResolverPicker as UpstreamPostCustomPostTypeResolverPicker;

class PostCustomPostTypeResolverPicker extends UpstreamPostCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPostCustomPostType();
    }
}
