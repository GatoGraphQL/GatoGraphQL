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
    protected PageObjectTypeResolver $pageObjectTypeResolver;
    protected PageTypeAPIInterface $pageTypeAPI;

    #[Required]
    final public function autowireAbstractPageObjectTypeResolverPicker(PageObjectTypeResolver $pageObjectTypeResolver, PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        $this->pageTypeAPI = $pageTypeAPI;
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
