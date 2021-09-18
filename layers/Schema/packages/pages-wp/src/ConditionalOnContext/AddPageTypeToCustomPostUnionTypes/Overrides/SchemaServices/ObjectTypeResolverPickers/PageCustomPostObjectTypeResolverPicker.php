<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Pages\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\PageCustomPostObjectTypeResolverPicker as UpstreamPageCustomPostObjectTypeResolverPicker;

class PageCustomPostObjectTypeResolverPicker extends UpstreamPageCustomPostObjectTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getPageCustomPostType();
    }
}
