<?php

declare(strict_types=1);

namespace PoP\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait ConfigurableCacheControlTypeResolverDecoratorTrait
{
    /**
     * By default, only the admin can see the roles from the users
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $maxAge = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $cacheControlDirectiveResolver = $instanceManager->getInstance(CacheControlDirectiveResolver::class);
        $cacheControlDirective = $fieldQueryInterpreter->getDirective(
            $cacheControlDirectiveResolver->getDirectiveName(),
            [
                'maxAge' => $maxAge,
            ]
        );
        return [
            $cacheControlDirective,
        ];
    }
}
