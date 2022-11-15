<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\Overrides\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\GenericCustomPostCustomPostObjectTypeResolverPicker as UpstreamGenericCustomPostCustomPostObjectTypeResolverPicker;

class GenericCustomPostCustomPostObjectTypeResolverPicker extends UpstreamGenericCustomPostCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        // @todo Fix here!!!!
        return '';
        // return $this->getCustomPostTypeAPI()->getCustomPostType();
    }
}
