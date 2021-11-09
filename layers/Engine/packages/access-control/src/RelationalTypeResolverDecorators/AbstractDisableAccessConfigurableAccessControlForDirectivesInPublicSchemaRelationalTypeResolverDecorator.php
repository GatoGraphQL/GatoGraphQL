<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver;

abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    private ?DisableAccessForDirectivesDirectiveResolver $disableAccessForDirectivesDirectiveResolver = null;

    final public function setDisableAccessForDirectivesDirectiveResolver(DisableAccessForDirectivesDirectiveResolver $disableAccessForDirectivesDirectiveResolver): void
    {
        $this->disableAccessForDirectivesDirectiveResolver = $disableAccessForDirectivesDirectiveResolver;
    }
    final protected function getDisableAccessForDirectivesDirectiveResolver(): DisableAccessForDirectivesDirectiveResolver
    {
        return $this->disableAccessForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(DisableAccessForDirectivesDirectiveResolver::class);
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $disableAccessDirective = $this->getFieldQueryInterpreter()->getDirective(
            $this->getDisableAccessForDirectivesDirectiveResolver()->getDirectiveName(),
            []
        );
        return [
            $disableAccessDirective,
        ];
    }
}
