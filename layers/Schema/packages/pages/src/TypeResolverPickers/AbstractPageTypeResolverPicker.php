<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;

abstract class AbstractPageTypeResolverPicker extends AbstractTypeResolverPicker
{
    public function getTypeResolverClass(): string
    {
        return PageTypeResolver::class;
    }

    public function isInstanceOfType($object): bool
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->isInstanceOfPageType($object);
    }

    public function isIDOfType($resultItemID): bool
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->pageExists($resultItemID);
    }
}
