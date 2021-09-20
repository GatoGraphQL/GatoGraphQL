<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

abstract class AbstractPageObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function __construct(
        protected PageObjectTypeResolver $pageObjectTypeResolver,
        protected PageTypeAPIInterface $pageTypeAPI,
    ) {
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->pageObjectTypeResolver;
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->pageTypeAPI->isInstanceOfPageType($object);
    }

    public function isIDOfType(string | int $objectID): bool
    {
        return $this->pageTypeAPI->pageExists($objectID);
    }
}
