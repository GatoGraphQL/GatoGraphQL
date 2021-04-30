<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\ConditionalOnContext\UseComponentModelCache\SchemaServices\TypeResolverDecorators;

use PoP\Engine\TypeResolvers\RootTypeResolver;

/**
 * Add directive @cache to fields expensive to calculate
 */
class RootCacheTypeResolverDecorator extends AbstractCacheTypeResolverDecorator
{
    public function getClassesToAttachTo(): array
    {
        return [
            RootTypeResolver::class,
        ];
    }

    /**
     * Get the fields to cache
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
