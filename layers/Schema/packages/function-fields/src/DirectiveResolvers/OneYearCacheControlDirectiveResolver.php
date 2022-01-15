<?php

declare(strict_types=1);

namespace PoP\FunctionFields\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;

class OneYearCacheControlDirectiveResolver extends AbstractCacheControlDirectiveResolver
{
    public function getFieldNamesToApplyTo(): array
    {
        return [
            'sprintf',
            'concat',
            'divide',
            'arrayRandom',
            'arrayJoin',
            'arrayItem',
            'arraySearch',
            'arrayFill',
            'arrayValues',
            'arrayUnique',
            'arrayDiff',
            'arrayAddItem',
            'arrayAsQueryStr',
            'objectAsQueryStr',
        ];
    }

    public function getMaxAge(): ?int
    {
        // One year = 315360000 seconds
        return 315360000;
    }
}
