<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP\TypeResolverPickers\Overrides;

use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;

class PageCustomPostTypeResolverPicker extends \PoPSchema\Pages\TypeResolverPickers\Optional\PageCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getPageCustomPostType();
    }
}
