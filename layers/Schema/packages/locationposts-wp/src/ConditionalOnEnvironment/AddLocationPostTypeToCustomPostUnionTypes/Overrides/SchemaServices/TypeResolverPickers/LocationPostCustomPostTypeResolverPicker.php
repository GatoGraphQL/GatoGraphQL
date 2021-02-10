<?php

declare(strict_types=1);

namespace PoPSchema\LocationPostsWP\ConditionalOnEnvironment\AddLocationPostTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\LocationPosts\ConditionalOnEnvironment\AddLocationPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\LocationPostCustomPostTypeResolverPicker as UpstreamLocationPostCustomPostTypeResolverPicker;

class LocationPostCustomPostTypeResolverPicker extends UpstreamLocationPostCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST;
    }
}
