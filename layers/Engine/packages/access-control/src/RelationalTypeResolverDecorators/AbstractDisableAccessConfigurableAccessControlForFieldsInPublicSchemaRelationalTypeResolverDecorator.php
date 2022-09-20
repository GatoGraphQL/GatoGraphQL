<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessFieldDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $disableAccessDirective = null;

    private ?DisableAccessFieldDirectiveResolver $disableAccessFieldDirectiveResolver = null;

    final public function setDisableAccessFieldDirectiveResolver(DisableAccessFieldDirectiveResolver $disableAccessFieldDirectiveResolver): void
    {
        $this->disableAccessFieldDirectiveResolver = $disableAccessFieldDirectiveResolver;
    }
    final protected function getDisableAccessFieldDirectiveResolver(): DisableAccessFieldDirectiveResolver
    {
        /** @var DisableAccessFieldDirectiveResolver */
        return $this->disableAccessFieldDirectiveResolver ??= $this->instanceManager->getInstance(DisableAccessFieldDirectiveResolver::class);
    }

    /**
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        return [
            $this->getDisableAccessDirective(),
        ];
    }

    protected function getDisableAccessDirective(): Directive
    {
        if ($this->disableAccessDirective === null) {
            $this->disableAccessDirective = new Directive(
                $this->getDisableAccessFieldDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->disableAccessDirective;
    }
}
