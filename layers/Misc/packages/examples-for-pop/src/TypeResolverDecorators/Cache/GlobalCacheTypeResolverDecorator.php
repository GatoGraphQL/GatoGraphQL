<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\TypeResolverDecorators\Cache;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use Leoloso\ExamplesForPoP\TypeResolverDecorators\Cache\AbstractCacheTypeResolverDecorator;

/**
 * Add directive @cache to fields expensive to calculate
 */
class GlobalCacheTypeResolverDecorator extends AbstractCacheTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return [
            AbstractTypeResolver::class,
        ];
    }

    /**
     * Get the fields to cache
     *
     * @return array
     */
    protected function getFieldNamesToCache(): array
    {
        return [
            'getJSON',
            'getAsyncJSON',
        ];
    }
}
