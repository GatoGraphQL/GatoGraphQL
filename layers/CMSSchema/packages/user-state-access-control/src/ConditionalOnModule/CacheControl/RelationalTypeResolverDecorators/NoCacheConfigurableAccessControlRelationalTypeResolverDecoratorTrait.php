<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

trait NoCacheConfigurableAccessControlRelationalTypeResolverDecoratorTrait
{
    /**
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        return [
            CacheControlHelper::getNoCacheDirective(),
        ];
    }
}
