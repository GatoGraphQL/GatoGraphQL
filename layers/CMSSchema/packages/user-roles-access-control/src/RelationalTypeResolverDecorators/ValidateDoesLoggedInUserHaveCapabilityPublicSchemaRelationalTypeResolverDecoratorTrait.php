<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

trait ValidateDoesLoggedInUserHaveCapabilityPublicSchemaRelationalTypeResolverDecoratorTrait
{
    protected ?Directive $validateDoesLoggedInUserHaveAnyCapabilityDirective = null;

    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;

    /**
     * By default, only the admin can see the roles from the users
     *
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $capabilities = $entryValue;
        return [
            $this->getValidateDoesLoggedInUserHaveAnyCapabilityDirective($capabilities),
        ];
    }

    /**
     * @param string[] $capabilities
     */
    protected function getValidateDoesLoggedInUserHaveAnyCapabilityDirective(array $capabilities): Directive
    {
        if ($this->validateDoesLoggedInUserHaveAnyCapabilityDirective === null) {
            $this->validateDoesLoggedInUserHaveAnyCapabilityDirective = new Directive(
                $this->getValidateCapabilityDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'capabilities',
                        new InputList(
                            $capabilities,
                            LocationHelper::getNonSpecificLocation()
                        ),
                        LocationHelper::getNonSpecificLocation()
                    ),
                ],
                LocationHelper::getNonSpecificLocation()
            );
        }
        return $this->validateDoesLoggedInUserHaveAnyCapabilityDirective;
    }

    abstract protected function getValidateCapabilityDirectiveResolver(): DirectiveResolverInterface;
}
