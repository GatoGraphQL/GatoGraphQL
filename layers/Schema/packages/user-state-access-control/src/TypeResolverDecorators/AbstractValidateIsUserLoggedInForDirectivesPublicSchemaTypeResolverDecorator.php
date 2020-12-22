<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    /**
     * Verify that the user is logged in before checking the roles/capabilities
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        // This is the required "validateIsUserLoggedIn" directive
        $validateIsUserLoggedInDirective = $fieldQueryInterpreter->getDirective(
            ValidateIsUserLoggedInForDirectivesDirectiveResolver::getDirectiveName()
        );
        // Add the mapping
        foreach ($this->getDirectiveResolverClasses() as $needValidateIsUserLoggedInDirective) {
            $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirective::getDirectiveName()] = [
                $validateIsUserLoggedInDirective,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }
    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return array
     */
    abstract protected function getDirectiveResolverClasses(): array;
}
