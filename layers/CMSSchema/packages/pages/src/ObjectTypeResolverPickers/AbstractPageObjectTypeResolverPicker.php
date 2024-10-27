<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

abstract class AbstractPageObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        if ($this->pageTypeAPI === null) {
            /** @var PageTypeAPIInterface */
            $pageTypeAPI = $this->instanceManager->getInstance(PageTypeAPIInterface::class);
            $this->pageTypeAPI = $pageTypeAPI;
        }
        return $this->pageTypeAPI;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getPageObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getPageTypeAPI()->isInstanceOfPageType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getPageTypeAPI()->pageExists($objectID);
    }
}
