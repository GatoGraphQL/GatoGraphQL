<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
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
        if ($directiveResolverClasses = $this->getDirectiveResolverClasses()) {
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $fieldQueryInterpreter->getDirective(
                ValidateIsUserLoggedInDirectiveResolver::getDirectiveName()
            );
            // Add the mapping
            foreach ($directiveResolverClasses as $needValidateIsUserLoggedInDirective) {
                $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirective::getDirectiveName()] = [
                    $validateIsUserLoggedInDirective,
                ];
            }
        }
        return $mandatoryDirectivesForDirectives;
    }
    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return array
     */
    protected function getDirectiveResolverClasses(): array
    {
        return [];
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if ($fieldNames = $this->getFieldNames()) {
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $fieldQueryInterpreter->getDirective(
                ValidateIsUserLoggedInDirectiveResolver::getDirectiveName()
            );
            // Add the mapping
            foreach ($fieldNames as $fieldName) {
                $mandatoryDirectivesForFields[$fieldName] = [
                    $validateIsUserLoggedInDirective,
                ];
            }
        }
        return $mandatoryDirectivesForFields;
    }
    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return array
     */
    protected function getFieldNames(): array
    {
        return [];
    }
}
