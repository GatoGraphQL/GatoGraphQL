<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesFieldDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $disableAccessDirective = null;

    private ?DisableAccessForDirectivesFieldDirectiveResolver $disableAccessForDirectivesFieldDirectiveResolver = null;

    final public function setDisableAccessForDirectivesFieldDirectiveResolver(DisableAccessForDirectivesFieldDirectiveResolver $disableAccessForDirectivesFieldDirectiveResolver): void
    {
        $this->disableAccessForDirectivesFieldDirectiveResolver = $disableAccessForDirectivesFieldDirectiveResolver;
    }
    final protected function getDisableAccessForDirectivesFieldDirectiveResolver(): DisableAccessForDirectivesFieldDirectiveResolver
    {
        /** @var DisableAccessForDirectivesFieldDirectiveResolver */
        return $this->disableAccessForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(DisableAccessForDirectivesFieldDirectiveResolver::class);
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
                $this->getDisableAccessForDirectivesFieldDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->disableAccessDirective;
    }
}
