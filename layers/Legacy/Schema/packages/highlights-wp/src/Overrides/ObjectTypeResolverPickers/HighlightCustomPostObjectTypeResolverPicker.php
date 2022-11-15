<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\Overrides\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Highlights\ObjectTypeResolverPickers\HighlightCustomPostObjectTypeResolverPicker as UpstreamHighlightCustomPostObjectTypeResolverPicker;

class HighlightCustomPostObjectTypeResolverPicker extends UpstreamHighlightCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }
}
