<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnComponent\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;

trait NoCacheConfigurableAccessControlRelationalTypeResolverDecoratorTrait
{
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        return [
            CacheControlHelper::getNoCacheDirective(),
        ];
    }
}
