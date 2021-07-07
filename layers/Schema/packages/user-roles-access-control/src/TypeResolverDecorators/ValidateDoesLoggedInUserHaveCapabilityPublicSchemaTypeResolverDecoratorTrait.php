<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait ValidateDoesLoggedInUserHaveCapabilityPublicSchemaTypeResolverDecoratorTrait
{
    /**
     * By default, only the admin can see the roles from the users
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $capabilities = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveResolverClass = $this->getValidateCapabilityDirectiveResolverClass();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
        $directiveName = $directiveResolver->getDirectiveName();
        $validateDoesLoggedInUserHaveAnyCapabilityDirective = $fieldQueryInterpreter->getDirective(
            $directiveName,
            [
                'capabilities' => $capabilities,
            ]
        );
        return [
            $validateDoesLoggedInUserHaveAnyCapabilityDirective,
        ];
    }

    abstract protected function getValidateCapabilityDirectiveResolverClass(): string;
}
