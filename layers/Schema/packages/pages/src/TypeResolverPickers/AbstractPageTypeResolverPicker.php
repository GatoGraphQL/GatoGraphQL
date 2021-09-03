<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;

abstract class AbstractPageTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function getObjectTypeResolverClass(): string
    {
        return PageTypeResolver::class;
    }

    public function isInstanceOfType(object $object): bool
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->isInstanceOfPageType($object);
    }

    public function isIDOfType(string | int $resultItemID): bool
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->pageExists($resultItemID);
    }
}
