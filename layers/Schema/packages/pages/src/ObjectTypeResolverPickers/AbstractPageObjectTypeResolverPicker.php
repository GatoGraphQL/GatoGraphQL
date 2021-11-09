<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

abstract class AbstractPageObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getPageObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getPageTypeAPI()->isInstanceOfPageType($object);
    }

    public function isIDOfType(string | int $objectID): bool
    {
        return $this->getPageTypeAPI()->pageExists($objectID);
    }
}
