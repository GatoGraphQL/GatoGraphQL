<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\Overrides\ObjectTypeResolverPickers;

use PoP\ComponentModel\Constants\ConfigurationValues;
use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\GenericCustomPostCustomPostObjectTypeResolverPicker as UpstreamGenericCustomPostCustomPostObjectTypeResolverPicker;

class GenericCustomPostCustomPostObjectTypeResolverPicker extends UpstreamGenericCustomPostCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        // As the Generic Custom Post can be any post type, return "*" to represent "any"
        return ConfigurationValues::ANY;
    }
}
