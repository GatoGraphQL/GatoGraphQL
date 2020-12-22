<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator
{
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $disableAccessDirective = $fieldQueryInterpreter->getDirective(
            DisableAccessDirectiveResolver::getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
