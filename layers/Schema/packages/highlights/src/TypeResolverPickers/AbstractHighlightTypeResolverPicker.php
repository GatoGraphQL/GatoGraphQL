<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeResolverPickers;

use PoPSchema\Highlights\Facades\HighlightTypeAPIFacade;
use PoPSchema\Highlights\TypeResolvers\HighlightTypeResolver;
use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;

class AbstractHighlightTypeResolverPicker extends AbstractTypeResolverPicker
{
    public function getTypeResolverClass(): string
    {
        return HighlightTypeResolver::class;
    }

    public function isInstanceOfType($object): bool
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->isInstanceOfHighlightType($object);
    }

    public function isIDOfType($resultItemID): bool
    {
        $highlightTypeAPI = HighlightTypeAPIFacade::getInstance();
        return $highlightTypeAPI->highlightExists($resultItemID);
    }
}
