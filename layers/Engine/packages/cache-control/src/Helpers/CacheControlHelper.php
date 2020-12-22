<?php

declare(strict_types=1);

namespace PoP\CacheControl\Helpers;

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;

class CacheControlHelper
{
    public static function getNoCacheDirective(): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        return $fieldQueryInterpreter->getDirective(
            AbstractCacheControlDirectiveResolver::getDirectiveName(),
            [
                'maxAge' => 0,
            ]
        );
    }
}
