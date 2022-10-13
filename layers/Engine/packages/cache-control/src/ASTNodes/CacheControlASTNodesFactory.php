<?php

declare(strict_types=1);

namespace PoP\CacheControl\ASTNodes;

use PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class CacheControlASTNodesFactory
{
    protected static ?Directive $noCacheDirective = null;

    protected static function getCacheControlFieldDirectiveResolver(): CacheControlFieldDirectiveResolver
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var CacheControlFieldDirectiveResolver */
        return $instanceManager->getInstance(CacheControlFieldDirectiveResolver::class);
    }

    public static function getNoCacheDirective(): Directive
    {
        if (self::$noCacheDirective === null) {
            $nonSpecificLocation = ASTNodesFactory::getNonSpecificLocation();
            self::$noCacheDirective = new Directive(
                static::getCacheControlFieldDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'maxAge',
                        new Literal(
                            0,
                            $nonSpecificLocation
                        ),
                        $nonSpecificLocation
                    ),
                ],
                $nonSpecificLocation
            );
        }
        return self::$noCacheDirective;
    }
}
