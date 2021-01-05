<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\TypeResolverDecorators\Cache;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use Leoloso\ExamplesForPoP\TypeResolverDecorators\Cache\AbstractCacheTypeResolverDecorator;

/**
 * Add directive @cache to fields expensive to calculate
 */
class RootCacheTypeResolverDecorator extends AbstractCacheTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return [
            RootTypeResolver::class,
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
            'meshServices',
            'meshServiceData',
            'contentMesh',
            'userServiceURLs',
            'userServiceData',
        ];
    }
}
