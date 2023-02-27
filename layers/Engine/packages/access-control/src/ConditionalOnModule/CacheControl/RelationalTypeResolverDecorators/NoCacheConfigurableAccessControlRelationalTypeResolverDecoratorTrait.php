<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\ASTNodes\CacheControlASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

trait NoCacheConfigurableAccessControlRelationalTypeResolverDecoratorTrait
{
    /**
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        return [
            CacheControlASTNodesFactory::getNoCacheDirective(),
        ];
    }
}
