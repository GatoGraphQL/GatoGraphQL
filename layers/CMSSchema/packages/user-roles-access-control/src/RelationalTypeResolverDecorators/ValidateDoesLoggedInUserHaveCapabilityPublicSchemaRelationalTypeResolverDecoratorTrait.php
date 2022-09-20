<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

trait ValidateDoesLoggedInUserHaveCapabilityPublicSchemaRelationalTypeResolverDecoratorTrait
{
    /** @var array<string,Directive> */
    protected array $validateDoesLoggedInUserHaveAnyCapabilityDirectives = [];

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
        $capabilitiesKey = implode('|', $capabilities);
        if (!isset($this->validateDoesLoggedInUserHaveAnyCapabilityDirectives[$capabilitiesKey])) {
            $this->validateDoesLoggedInUserHaveAnyCapabilityDirectives[$capabilitiesKey] = new Directive(
                $this->getValidateCapabilityFieldDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'capabilities',
                        new InputList(
                            $capabilities,
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                ],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->validateDoesLoggedInUserHaveAnyCapabilityDirectives[$capabilitiesKey];
    }

    abstract protected function getValidateCapabilityFieldDirectiveResolver(): FieldDirectiveResolverInterface;
}
