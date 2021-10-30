<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait ConfigurableCacheControlRelationalTypeResolverDecoratorTrait
{
    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;
    abstract protected function getCacheControlDirectiveResolver(): CacheControlDirectiveResolver;

    /**
     * By default, only the admin can see the roles from the users
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $maxAge = $entryValue;
        $cacheControlDirective = $this->getFieldQueryInterpreter()->getDirective(
            $this->getCacheControlDirectiveResolver()->getDirectiveName(),
            [
                'maxAge' => $maxAge,
            ]
        );
        return [
            $cacheControlDirective,
        ];
    }
}
