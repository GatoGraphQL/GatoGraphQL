<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

/**
 * Add directive @cache to fields expensive to calculate
 */
class GlobalCacheTypeResolverDecorator extends AbstractCacheTypeResolverDecorator
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractTypeResolver::class,
        ];
    }

    /**
     * Get the fields to cache
     */
    protected function getFieldNamesToCache(): array
    {
        return [
            'getJSON',
            'getAsyncJSON',
        ];
    }
}
