<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

trait UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait
{
    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $validateUserStateDirectiveResolver = $this->getValidateUserStateDirectiveResolver();
        $validateUserStateDirectiveName = $validateUserStateDirectiveResolver->getDirectiveName();
        $validateUserStateDirective = $this->getFieldQueryInterpreter()->getDirective(
            $validateUserStateDirectiveName,
            []
        );
        return [
            $validateUserStateDirective,
        ];
    }

    abstract protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface;
}
