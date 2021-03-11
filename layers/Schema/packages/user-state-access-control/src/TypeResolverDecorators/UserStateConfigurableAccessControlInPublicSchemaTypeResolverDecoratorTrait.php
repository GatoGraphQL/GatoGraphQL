<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

trait UserStateConfigurableAccessControlInPublicSchemaTypeResolverDecoratorTrait
{
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        $validateUserStateDirectiveResolverClass = $this->getValidateUserStateDirectiveResolverClass();
        /** @var DirectiveResolverInterface */
        $validateUserStateDirectiveResolver = $instanceManager->getInstance($validateUserStateDirectiveResolverClass);
        $validateUserStateDirectiveName = $validateUserStateDirectiveResolver->getDirectiveName();
        $validateUserStateDirective = $fieldQueryInterpreter->getDirective(
            $validateUserStateDirectiveName
        );
        return [
            $validateUserStateDirective,
        ];
    }

    abstract protected function getValidateUserStateDirectiveResolverClass(): string;
}
