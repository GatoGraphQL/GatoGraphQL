<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\ConditionalOnContext\AddHighlightTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Highlights\ConditionalOnContext\AddHighlightTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\HighlightCustomPostObjectTypeResolverPicker as UpstreamHighlightCustomPostObjectTypeResolverPicker;

class HighlightCustomPostObjectTypeResolverPicker extends UpstreamHighlightCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }
}
