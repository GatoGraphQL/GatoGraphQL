<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    private ?DisableAccessDirectiveResolver $disableAccessDirectiveResolver = null;

    final public function setDisableAccessDirectiveResolver(DisableAccessDirectiveResolver $disableAccessDirectiveResolver): void
    {
        $this->disableAccessDirectiveResolver = $disableAccessDirectiveResolver;
    }
    final protected function getDisableAccessDirectiveResolver(): DisableAccessDirectiveResolver
    {
        return $this->disableAccessDirectiveResolver ??= $this->instanceManager->getInstance(DisableAccessDirectiveResolver::class);
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $disableAccessDirective = $this->getFieldQueryInterpreter()->getDirective(
            $this->getDisableAccessDirectiveResolver()->getDirectiveName(),
            []
        );
        return [
            $disableAccessDirective,
        ];
    }
}
