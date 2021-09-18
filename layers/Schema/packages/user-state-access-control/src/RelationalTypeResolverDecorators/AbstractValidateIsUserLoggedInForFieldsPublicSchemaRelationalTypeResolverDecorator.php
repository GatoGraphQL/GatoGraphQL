<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    /**
     * Verify that the user is logged in before checking the roles/capabilities
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        if ($directiveResolvers = $this->getDirectiveResolvers()) {
            /** @var DirectiveResolverInterface */
            $validateIsUserLoggedInDirectiveResolver = $this->instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $this->fieldQueryInterpreter->getDirective(
                $validateIsUserLoggedInDirectiveResolver->getDirectiveName()
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
            /** @var DirectiveResolverInterface */
            $validateIsUserLoggedInDirectiveResolver = $this->instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $this->fieldQueryInterpreter->getDirective(
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
     * Provide the fields that need the "validateIsUserLoggedIn" directive
     */
    protected function getFieldNames(): array
    {
        return [];
    }
}
