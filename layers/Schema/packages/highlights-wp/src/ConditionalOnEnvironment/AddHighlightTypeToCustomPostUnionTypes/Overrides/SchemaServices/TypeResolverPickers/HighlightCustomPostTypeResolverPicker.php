<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\ConditionalOnEnvironment\AddHighlightTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Highlights\ConditionalOnEnvironment\AddHighlightTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\HighlightCustomPostTypeResolverPicker as UpstreamHighlightCustomPostTypeResolverPicker;

class HighlightCustomPostTypeResolverPicker extends UpstreamHighlightCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }
}
