<?php

declare(strict_types=1);

namespace PoP\CacheControl\Helpers;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class CacheControlHelper
{
    protected static ?Directive $noCacheDirective = null;

    final protected static function getCacheControlDirectiveResolver(): CacheControlDirectiveResolver
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(CacheControlDirectiveResolver::class);
    }

    public static function getNoCacheDirective(): Directive
    {
        if (self::$noCacheDirective === null) {
            self::$noCacheDirective = new Directive(
                static::getCacheControlDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'maxAge',
                        new Literal(
                            0,
                            LocationHelper::getNonSpecificLocation()
                        ),
                        LocationHelper::getNonSpecificLocation()
                    ),
                ],
                LocationHelper::getNonSpecificLocation()
            );
        }
        return self::$noCacheDirective;
    }
}
