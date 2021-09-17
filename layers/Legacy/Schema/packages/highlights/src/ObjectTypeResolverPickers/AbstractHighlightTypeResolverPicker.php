<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\TypeResolvers\ObjectType\HighlightObjectTypeResolver;

class AbstractHighlightTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function __construct(
        protected HighlightObjectTypeResolver $highlightObjectTypeResolver,
    ) {        
    }
    
    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->highlightObjectTypeResolver;
    }

    public function isInstanceOfType(object $object): bool
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->isInstanceOfHighlightType($object);
    }

    public function isIDOfType(string | int $objectID): bool
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->highlightExists($objectID);
    }
}
