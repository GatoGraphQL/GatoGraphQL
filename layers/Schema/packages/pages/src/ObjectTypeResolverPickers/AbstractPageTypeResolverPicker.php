<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageTypeResolver;

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
