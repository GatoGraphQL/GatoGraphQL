<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator
{
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        /** @var DirectiveResolverInterface */
        $disableAccessDirectiveResolver = $this->instanceManager->getInstance(DisableAccessDirectiveResolver::class);
        $disableAccessDirective = $this->fieldQueryInterpreter->getDirective(
            $disableAccessDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
