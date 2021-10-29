<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractPageObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }

    //#[Required]
    final public function autowireAbstractPageObjectTypeResolverPicker(PageObjectTypeResolver $pageObjectTypeResolver, PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        $this->pageTypeAPI = $pageTypeAPI;
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
