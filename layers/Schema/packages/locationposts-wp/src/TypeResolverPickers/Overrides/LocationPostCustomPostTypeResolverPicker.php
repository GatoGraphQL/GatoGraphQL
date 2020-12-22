<?php

declare(strict_types=1);

namespace PoPSchema\LocationPostsWP\TypeResolverPickers\Overrides;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;

class LocationPostCustomPostTypeResolverPicker extends \PoPSchema\LocationPosts\TypeResolverPickers\Optional\LocationPostCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST;
    }
}
