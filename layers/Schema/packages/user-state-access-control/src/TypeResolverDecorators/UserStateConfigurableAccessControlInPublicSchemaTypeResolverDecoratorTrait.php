<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

trait UserStateConfigurableAccessControlInPublicSchemaTypeResolverDecoratorTrait
{
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $validateUserStateDirectiveClass = $this->getValidateUserStateDirectiveResolverClass();
        $validateUserStateDirectiveName = $validateUserStateDirectiveClass::getDirectiveName();
        $validateUserStateDirective = $fieldQueryInterpreter->getDirective(
            $validateUserStateDirectiveName
        );
        return [
            $validateUserStateDirective,
        ];
    }

    abstract protected function getValidateUserStateDirectiveResolverClass(): string;
}
