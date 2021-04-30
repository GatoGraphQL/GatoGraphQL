<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    /**
     * Verify that the user is logged in before checking the roles/capabilities
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        /** @var DirectiveResolverInterface */
        $validateIsUserLoggedInForDirectivesDirectiveResolver = $this->instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesDirectiveResolver::class);
        // This is the required "validateIsUserLoggedIn" directive
        $validateIsUserLoggedInDirective = $this->fieldQueryInterpreter->getDirective(
            $validateIsUserLoggedInForDirectivesDirectiveResolver->getDirectiveName()
        );
        // Add the mapping
        foreach ($this->getDirectiveResolverClasses() as $needValidateIsUserLoggedInDirectiveResolverClass) {
            /** @var DirectiveResolverInterface */
            $needValidateIsUserLoggedInDirectiveResolver = $this->instanceManager->getInstance($needValidateIsUserLoggedInDirectiveResolverClass);
            $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirectiveResolver->getDirectiveName()] = [
                $validateIsUserLoggedInDirective,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }
    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return string[]
     */
    abstract protected function getDirectiveResolverClasses(): array;
}
