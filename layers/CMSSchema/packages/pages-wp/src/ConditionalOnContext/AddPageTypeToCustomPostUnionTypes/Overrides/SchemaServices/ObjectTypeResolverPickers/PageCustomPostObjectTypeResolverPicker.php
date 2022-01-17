<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Pages\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\PageCustomPostObjectTypeResolverPicker as UpstreamPageCustomPostObjectTypeResolverPicker;

class PageCustomPostObjectTypeResolverPicker extends UpstreamPageCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return $this->getPageTypeAPI()->getPageCustomPostType();
    }
}
