<?php

declare(strict_types=1);

namespace PoPSchema\LocationPostsWP\ConditionalOnContext\AddLocationPostTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\LocationPosts\ConditionalOnContext\AddLocationPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\LocationPostCustomPostTypeResolverPicker as UpstreamLocationPostCustomPostTypeResolverPicker;

class LocationPostCustomPostTypeResolverPicker extends UpstreamLocationPostCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST;
    }
}
