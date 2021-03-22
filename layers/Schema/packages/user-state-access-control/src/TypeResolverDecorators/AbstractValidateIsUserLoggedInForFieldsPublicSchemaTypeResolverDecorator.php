<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    /**
     * Verify that the user is logged in before checking the roles/capabilities
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        if ($directiveResolverClasses = $this->getDirectiveResolverClasses()) {
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var DirectiveResolverInterface */
            $validateIsUserLoggedInDirectiveResolver = $instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $fieldQueryInterpreter->getDirective(
                $validateIsUserLoggedInDirectiveResolver->getDirectiveName()
            );
            // Add the mapping
            foreach ($directiveResolverClasses as $needValidateIsUserLoggedInDirectiveResolverClass) {
                /** @var DirectiveResolverInterface */
                $needValidateIsUserLoggedInDirectiveResolver = $instanceManager->getInstance($needValidateIsUserLoggedInDirectiveResolverClass);
                $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirectiveResolver->getDirectiveName()] = [
                    $validateIsUserLoggedInDirective,
                ];
            }
        }
        return $mandatoryDirectivesForDirectives;
    }
    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     */
    protected function getDirectiveResolverClasses(): array
    {
        return [];
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if ($fieldNames = $this->getFieldNames()) {
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var DirectiveResolverInterface */
            $validateIsUserLoggedInDirectiveResolver = $instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $fieldQueryInterpreter->getDirective(
                $validateIsUserLoggedInDirectiveResolver->getDirectiveName()
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
     */
    protected function getFieldNames(): array
    {
        return [];
    }
}
