<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\TypeResolverPickers\Overrides;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;

class HighlightCustomPostTypeResolverPicker extends \PoPSchema\Highlights\TypeResolverPickers\Optional\HighlightCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }
}
