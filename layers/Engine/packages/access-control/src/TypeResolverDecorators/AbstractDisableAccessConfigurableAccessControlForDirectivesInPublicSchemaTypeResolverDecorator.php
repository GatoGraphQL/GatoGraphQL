<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        /** @var DirectiveResolverInterface */
        $disableAccessForDirectivesDirectiveResolver = $this->instanceManager->getInstance(DisableAccessForDirectivesDirectiveResolver::class);
        $disableAccessDirective = $this->fieldQueryInterpreter->getDirective(
            $disableAccessForDirectivesDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
