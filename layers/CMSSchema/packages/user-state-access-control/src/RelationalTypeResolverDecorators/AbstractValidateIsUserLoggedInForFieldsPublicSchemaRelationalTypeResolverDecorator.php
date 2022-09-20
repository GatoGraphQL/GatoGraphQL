<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInFieldDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $validateIsUserLoggedInDirective = null;

    private ?ValidateIsUserLoggedInFieldDirectiveResolver $validateIsUserLoggedInFieldDirectiveResolver = null;

    final public function setValidateIsUserLoggedInFieldDirectiveResolver(ValidateIsUserLoggedInFieldDirectiveResolver $validateIsUserLoggedInFieldDirectiveResolver): void
    {
        $this->validateIsUserLoggedInFieldDirectiveResolver = $validateIsUserLoggedInFieldDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInFieldDirectiveResolver(): ValidateIsUserLoggedInFieldDirectiveResolver
    {
        /** @var ValidateIsUserLoggedInFieldDirectiveResolver */
        return $this->validateIsUserLoggedInFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInFieldDirectiveResolver::class);
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     * @return array<string,Directive[]>
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        if ($directiveResolvers = $this->getFieldDirectiveResolvers()) {
            // This is the required "validateIsUserLoggedIn" directive
            $validateIsUserLoggedInDirective = $this->getValidateIsUserLoggedInDirective();
            // Add the mapping
            foreach ($directiveResolvers as $needValidateIsUserLoggedInFieldDirectiveResolver) {
                $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInFieldDirectiveResolver->getDirectiveName()] = [
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
                $this->getValidateIsUserLoggedInFieldDirectiveResolver()->getDirectiveName(),
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
    protected function getFieldDirectiveResolvers(): array
    {
        return [];
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     *
     * @return array<string,Directive[]> Key: fieldName or "*" (for any field), Value: List of Directives
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
