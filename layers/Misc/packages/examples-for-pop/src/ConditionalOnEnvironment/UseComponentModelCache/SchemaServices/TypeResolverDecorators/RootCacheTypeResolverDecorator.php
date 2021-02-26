<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\TypeResolverDecorators;

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
