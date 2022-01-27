<?php

declare(strict_types=1);

namespace PoPSchema\Highlights;

class Environment
{
    public static function addHighlightTypeToCustomPostUnionTypes(): bool
    {
        return getenv('ADD_HIGHLIGHT_TYPE_TO_CUSTOMPOST_UNION_TYPES') !== false ? strtolower(getenv('ADD_HIGHLIGHT_TYPE_TO_CUSTOMPOST_UNION_TYPES')) === "true" : false;
    }
}
