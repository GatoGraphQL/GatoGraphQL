<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Pages\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\PageCustomPostTypeResolverPicker as UpstreamPageCustomPostTypeResolverPicker;

class PageCustomPostTypeResolverPicker extends UpstreamPageCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getPageCustomPostType();
    }
}
