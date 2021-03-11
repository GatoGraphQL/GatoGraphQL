<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator
{
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $disableAccessDirectiveResolver = $instanceManager->getInstance(DisableAccessDirectiveResolver::class);
        $disableAccessDirective = $fieldQueryInterpreter->getDirective(
            $disableAccessDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
