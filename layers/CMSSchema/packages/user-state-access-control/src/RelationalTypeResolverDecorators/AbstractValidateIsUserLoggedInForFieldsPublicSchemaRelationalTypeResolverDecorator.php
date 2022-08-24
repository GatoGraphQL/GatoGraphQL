<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $validateIsUserLoggedInDirective = null;

    private ?ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver = null;

    final public function setValidateIsUserLoggedInDirectiveResolver(ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver): void
    {
        $this->validateIsUserLoggedInDirectiveResolver = $validateIsUserLoggedInDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInDirectiveResolver(): ValidateIsUserLoggedInDirectiveResolver
    {
        /** @var ValidateIsUserLoggedInDirectiveResolver */
        return $this->validateIsUserLoggedInDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     * @return array<string,Directive[]>
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        if ($directiveResolvers = $this->getDirectiveResolvers()) {
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $this->getValidateIsUserLoggedInDirective();
            // Add the mapping
            foreach ($directiveResolvers as $needValidateIsUserLoggedInDirectiveResolver) {
                $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirectiveResolver->getDirectiveName()] = [
                    $validateIsUserLoggedInDirective,
                ];
            }
        }
        return $mandatoryDirectivesForDirectives;
    }

    protected function getValidateIsUserLoggedInDirective(): Directive
    {
        if ($this->validateIsUserLoggedInDirective === null) {
            $this->validateIsUserLoggedInDirective = new Directive(
                $this->getValidateIsUserLoggedInDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->validateIsUserLoggedInDirective;
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
     *
     * @return array<string,Directive[]> Key: fieldName, Value: List of Directives
     */
    public function getMandatoryDirectivesForFields(ObjectTypeResolverInterface $objectTypeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if ($fieldNames = $this->getFieldNames()) {
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $this->getValidateIsUserLoggedInDirective();
            // Add the mapping
            foreach ($fieldNames as $fieldName) {
                $mandatoryDirectivesForFields[$fieldName] = [
                    $validateIsUserLoggedInDirective,
                ];
            }
        }
        /** @var array<string,Directive[]> */
        return $mandatoryDirectivesForFields;
    }
    /**
     * Provide the fields that need the "validateIsUserLoggedIn" directive
     * @return mixed[]
     */
    protected function getFieldNames(): array
    {
        return [];
    }
}
