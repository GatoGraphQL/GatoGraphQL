<?php

declare(strict_types=1);

namespace PoP\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

trait ConfigurableCacheControlTypeResolverDecoratorTrait
{
    /**
     * By default, only the admin can see the roles from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $maxAge = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveName = AbstractCacheControlDirectiveResolver::getDirectiveName();
        $cacheControlDirective = $fieldQueryInterpreter->getDirective(
            $directiveName,
            [
                'maxAge' => $maxAge,
            ]
        );
        return [
            $cacheControlDirective,
        ];
    }
}
