<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

trait ConfigurableCacheControlRelationalTypeResolverDecoratorTrait
{
    /**
     * @var array<string|int,Directive>
     */
    protected array $cacheControlDirectives = [];

    abstract protected function getCacheControlFieldDirectiveResolver(): CacheControlFieldDirectiveResolver;

    /**
     * By default, only the admin can see the roles from the users
     *
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $maxAge = $entryValue;
        return [
            $this->getCacheControlDirective($maxAge),
        ];
    }

    protected function getCacheControlDirective(string|int $maxAge): Directive
    {
        if (!isset($this->cacheControlDirectives[$maxAge])) {
            $nonSpecificLocation = ASTNodesFactory::getNonSpecificLocation();
            $this->cacheControlDirectives[$maxAge] = new Directive(
                $this->getCacheControlFieldDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'maxAge',
                        new Literal(
                            $maxAge,
                            $nonSpecificLocation
                        ),
                        $nonSpecificLocation
                    ),
                ],
                $nonSpecificLocation
            );
        }
        return $this->cacheControlDirectives[$maxAge];
    }
}
