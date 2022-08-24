<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\TypeResolvers\ObjectType\HighlightObjectTypeResolver;

abstract class AbstractHighlightObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?HighlightObjectTypeResolver $highlightObjectTypeResolver = null;
    
    final public function setHighlightObjectTypeResolver(HighlightObjectTypeResolver $highlightObjectTypeResolver): void
    {
        $this->highlightObjectTypeResolver = $highlightObjectTypeResolver;
    }
    final protected function getHighlightObjectTypeResolver(): HighlightObjectTypeResolver
    {
        /** @var HighlightObjectTypeResolver */
        return $this->highlightObjectTypeResolver ??= $this->instanceManager->getInstance(HighlightObjectTypeResolver::class);
    }
    
    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getHighlightObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->isInstanceOfHighlightType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->highlightExists($objectID);
    }
}
