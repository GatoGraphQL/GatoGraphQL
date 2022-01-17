<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

trait ValidateDoesLoggedInUserHaveCapabilityPublicSchemaRelationalTypeResolverDecoratorTrait
{
    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;

    /**
     * By default, only the admin can see the roles from the users
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $capabilities = $entryValue;
        $directiveResolver = $this->getValidateCapabilityDirectiveResolver();
        $directiveName = $directiveResolver->getDirectiveName();
        $validateDoesLoggedInUserHaveAnyCapabilityDirective = $this->getFieldQueryInterpreter()->getDirective(
            $directiveName,
            [
                'capabilities' => $capabilities,
            ]
        );
        return [
            $validateDoesLoggedInUserHaveAnyCapabilityDirective,
        ];
    }

    abstract protected function getValidateCapabilityDirectiveResolver(): DirectiveResolverInterface;
}
