<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    private ?DisableAccessDirectiveResolver $disableAccessDirectiveResolver = null;

    public function setDisableAccessDirectiveResolver(DisableAccessDirectiveResolver $disableAccessDirectiveResolver): void
    {
        $this->disableAccessDirectiveResolver = $disableAccessDirectiveResolver;
    }
    protected function getDisableAccessDirectiveResolver(): DisableAccessDirectiveResolver
    {
        return $this->disableAccessDirectiveResolver ??= $this->instanceManager->getInstance(DisableAccessDirectiveResolver::class);
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $disableAccessDirective = $this->getFieldQueryInterpreter()->getDirective(
            $this->getDisableAccessDirectiveResolver()->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
