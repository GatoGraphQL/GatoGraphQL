<?php

declare(strict_types=1);

namespace PoP\CacheControl\Helpers;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class CacheControlHelper
{
    public static function getNoCacheDirective(): Directive
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $cacheControlDirectiveResolver = $instanceManager->getInstance(CacheControlDirectiveResolver::class);
        return new Directive(
            $cacheControlDirectiveResolver->getDirectiveName(),
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
}
