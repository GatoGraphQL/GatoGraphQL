<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\ObjectTypeResolverPickers;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\TypeResolvers\ObjectType\HighlightObjectTypeResolver;

abstract class AbstractHighlightObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    protected HighlightObjectTypeResolver $highlightObjectTypeResolver;
    
    #[Required]
    public function autowireAbstractHighlightObjectTypeResolverPicker(HighlightObjectTypeResolver $highlightObjectTypeResolver): void
    {
        $this->highlightObjectTypeResolver = $highlightObjectTypeResolver;
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
