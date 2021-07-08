<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\ConditionalOnContext\AddHighlightTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Highlights\ConditionalOnContext\AddHighlightTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\HighlightCustomPostTypeResolverPicker as UpstreamHighlightCustomPostTypeResolverPicker;

class HighlightCustomPostTypeResolverPicker extends UpstreamHighlightCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }
}
