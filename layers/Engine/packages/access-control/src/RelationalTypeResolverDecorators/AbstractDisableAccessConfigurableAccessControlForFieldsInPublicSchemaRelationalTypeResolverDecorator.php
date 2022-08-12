<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $disableAccessDirective = null;

    private ?DisableAccessDirectiveResolver $disableAccessDirectiveResolver = null;

    final public function setDisableAccessDirectiveResolver(DisableAccessDirectiveResolver $disableAccessDirectiveResolver): void
    {
        $this->disableAccessDirectiveResolver = $disableAccessDirectiveResolver;
    }
    final protected function getDisableAccessDirectiveResolver(): DisableAccessDirectiveResolver
    {
        return $this->disableAccessDirectiveResolver ??= $this->instanceManager->getInstance(DisableAccessDirectiveResolver::class);
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
                $this->getDisableAccessDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->disableAccessDirective;
    }
}
