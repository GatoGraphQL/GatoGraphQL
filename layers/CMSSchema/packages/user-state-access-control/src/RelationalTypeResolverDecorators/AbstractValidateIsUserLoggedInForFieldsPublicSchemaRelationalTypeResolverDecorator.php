<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    private ?ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver = null;

    final public function setValidateIsUserLoggedInDirectiveResolver(ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver): void
    {
        $this->validateIsUserLoggedInDirectiveResolver = $validateIsUserLoggedInDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInDirectiveResolver(): ValidateIsUserLoggedInDirectiveResolver
    {
        return $this->validateIsUserLoggedInDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        if ($directiveResolvers = $this->getDirectiveResolvers()) {
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $this->getFieldQueryInterpreter()->getDirective(
                $this->getValidateIsUserLoggedInDirectiveResolver()->getDirectiveName(),
                []
            );
            // Add the mapping
            foreach ($directiveResolvers as $needValidateIsUserLoggedInDirectiveResolver) {
                $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirectiveResolver->getDirectiveName()] = [
                    $validateIsUserLoggedInDirective,
                ];
            }
        }
        return $mandatoryDirectivesForDirectives;
    }
    /**
     * Provide the DirectiveResolvers that need the "validateIsUserLoggedIn" directive
     *
     * @return DirectiveResolverInterface[]
     */
    protected function getDirectiveResolvers(): array
    {
        return [];
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     */
    public function getMandatoryDirectivesForFields(ObjectTypeResolverInterface $objectTypeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if ($fieldNames = $this->getFieldNames()) {
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $this->getFieldQueryInterpreter()->getDirective(
                $this->getValidateIsUserLoggedInDirectiveResolver()->getDirectiveName(),
                []
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
     * Provide the fields that need the "validateIsUserLoggedIn" directive
     */
    protected function getFieldNames(): array
    {
        return [];
    }
}
