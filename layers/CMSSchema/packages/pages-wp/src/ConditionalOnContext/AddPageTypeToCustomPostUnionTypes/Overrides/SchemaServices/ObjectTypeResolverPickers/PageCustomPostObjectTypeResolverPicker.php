<?php

declare(strict_types=1);

namespace PoPCMSSchema\PagesWP\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPCMSSchema\Pages\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\PageCustomPostObjectTypeResolverPicker as UpstreamPageCustomPostObjectTypeResolverPicker;

class PageCustomPostObjectTypeResolverPicker extends UpstreamPageCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return $this->getPageTypeAPI()->getPageCustomPostType();
    }
}
