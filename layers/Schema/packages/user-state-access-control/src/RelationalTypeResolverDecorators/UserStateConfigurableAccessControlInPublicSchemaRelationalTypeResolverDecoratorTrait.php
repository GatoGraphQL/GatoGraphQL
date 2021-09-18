<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

trait UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait
{
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        $validateUserStateDirectiveResolverClass = $this->getValidateUserStateDirectiveResolver();
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

    abstract protected function getValidateUserStateDirectiveResolver(): string;
}
