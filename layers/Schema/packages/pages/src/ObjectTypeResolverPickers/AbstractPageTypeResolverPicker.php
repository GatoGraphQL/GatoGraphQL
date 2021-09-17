<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

abstract class AbstractPageTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function __construct(
        protected PageObjectTypeResolver $pageObjectTypeResolver,
    ) {
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->pageObjectTypeResolver;
    }

    public function isInstanceOfType(object $object): bool
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->isInstanceOfPageType($object);
    }

    public function isIDOfType(string | int $objectID): bool
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->pageExists($objectID);
    }
}
