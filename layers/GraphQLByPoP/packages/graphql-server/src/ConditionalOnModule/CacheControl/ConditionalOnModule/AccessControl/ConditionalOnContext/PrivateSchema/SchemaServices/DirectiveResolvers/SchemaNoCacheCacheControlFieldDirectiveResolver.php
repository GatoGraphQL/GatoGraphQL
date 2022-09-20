<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ConditionalOnModule\CacheControl\ConditionalOnModule\AccessControl\ConditionalOnContext\PrivateSchema\SchemaServices\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlFieldDirectiveResolver;

/**
 * This class is not to be initialized immediately, but only depending on the values of environment variables
 */
class SchemaNoCacheCacheControlFieldDirectiveResolver extends AbstractCacheControlFieldDirectiveResolver
{
    /**
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        return [
            '__schema',
        ];
    }

    public function getMaxAge(): ?int
    {
        // Do not cache
        return 0;
    }
}
