<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
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

    abstract protected function getCacheControlDirectiveResolver(): CacheControlDirectiveResolver;

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
            $this->cacheControlDirectives[$maxAge] = new Directive(
                $this->getCacheControlDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'maxAge',
                        new Literal(
                            $maxAge,
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                ],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->cacheControlDirectives[$maxAge];
    }
}
