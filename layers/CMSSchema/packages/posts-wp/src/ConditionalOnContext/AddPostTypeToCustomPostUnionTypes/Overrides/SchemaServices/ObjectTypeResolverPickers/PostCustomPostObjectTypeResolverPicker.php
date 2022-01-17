<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostsWP\ConditionalOnContext\AddPostTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPCMSSchema\Posts\ConditionalOnContext\AddPostTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\PostCustomPostObjectTypeResolverPicker as UpstreamPostCustomPostObjectTypeResolverPicker;

class PostCustomPostObjectTypeResolverPicker extends UpstreamPostCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }
}
