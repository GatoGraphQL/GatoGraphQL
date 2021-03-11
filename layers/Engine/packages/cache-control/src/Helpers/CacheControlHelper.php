<?php

declare(strict_types=1);

namespace PoP\CacheControl\Helpers;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class CacheControlHelper
{
    public static function getNoCacheDirective(): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $cacheControlDirectiveResolver = $instanceManager->getInstance(CacheControlDirectiveResolver::class);
        return $fieldQueryInterpreter->getDirective(
            $cacheControlDirectiveResolver->getDirectiveName(),
            [
                'maxAge' => 0,
            ]
        );
    }
}
