<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;

class OneYearCacheControlDirectiveResolver extends AbstractCacheControlDirectiveResolver
{
    /**
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        return [
            'id',
            // operators and helpers...
            'if',
            'not',
            'and',
            'or',
            'equals',
            'empty',
            'isNull',
            'extract',
            'echo',
        ];
    }

    public function getMaxAge(): ?int
    {
        // One year = 315360000 seconds
        return 315360000;
    }
}
