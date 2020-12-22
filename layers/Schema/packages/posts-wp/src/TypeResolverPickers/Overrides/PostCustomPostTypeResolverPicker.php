<?php

declare(strict_types=1);

namespace PoPSchema\PostsWP\TypeResolverPickers\Overrides;

use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;

class PostCustomPostTypeResolverPicker extends \PoPSchema\Posts\TypeResolverPickers\Optional\PostCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPostCustomPostType();
    }
}
