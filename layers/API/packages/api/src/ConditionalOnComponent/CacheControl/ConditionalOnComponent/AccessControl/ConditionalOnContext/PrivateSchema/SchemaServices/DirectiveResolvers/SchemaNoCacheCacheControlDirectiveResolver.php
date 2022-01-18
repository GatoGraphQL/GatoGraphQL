<?php

declare(strict_types=1);

namespace PoPAPI\API\ConditionalOnComponent\CacheControl\ConditionalOnComponent\AccessControl\ConditionalOnContext\PrivateSchema\SchemaServices\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;

/**
 * This class is not to be initialized immediately, but only depending on the values of environment variables
 */
class SchemaNoCacheCacheControlDirectiveResolver extends AbstractCacheControlDirectiveResolver
{
    public function getFieldNamesToApplyTo(): array
    {
        return [
            'fullSchema',
        ];
    }

    public function getMaxAge(): ?int
    {
        // Do not cache
        return 0;
    }
}
