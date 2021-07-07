<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;

trait NoCacheConfigurableAccessControlTypeResolverDecoratorTrait
{
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        return [
            CacheControlHelper::getNoCacheDirective(),
        ];
    }
}
