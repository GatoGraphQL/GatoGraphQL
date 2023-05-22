<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnModule\CacheControl\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlFieldDirectiveResolver;

class OneYearCacheControlFieldDirectiveResolver extends AbstractCacheControlFieldDirectiveResolver
{
    /**
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        return [
            'id',
            'globalID',
        ];
    }

    public function getMaxAge(): ?int
    {
        // One year = 315360000 seconds
        return 315360000;
    }
}
