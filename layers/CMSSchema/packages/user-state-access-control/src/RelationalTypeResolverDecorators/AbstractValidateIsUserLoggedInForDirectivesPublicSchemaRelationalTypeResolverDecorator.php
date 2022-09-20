<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $validateIsUserLoggedInDirective = null;

    private ?ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver $validateIsUserLoggedInForDirectivesFieldDirectiveResolver = null;

    final public function setValidateIsUserLoggedInForDirectivesFieldDirectiveResolver(ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver $validateIsUserLoggedInForDirectivesFieldDirectiveResolver): void
    {
        $this->validateIsUserLoggedInForDirectivesFieldDirectiveResolver = $validateIsUserLoggedInForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInForDirectivesFieldDirectiveResolver(): ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver */
        return $this->validateIsUserLoggedInForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver::class);
    }

    /**
     * Verify that the user is logged in before checking the roles/capabilities
     * @return array<string,Directive[]>
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        // This is the required "validateIsUserLoggedIn" directive
        $validateIsUserLoggedInDirective = $this->getValidateIsUserLoggedInDirective();
        // Add the mapping
        foreach ($this->getFieldDirectiveResolvers() as $needValidateIsUserLoggedInFieldDirectiveResolver) {
            $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInFieldDirectiveResolver->getDirectiveName()] = [
                $validateIsUserLoggedInDirective,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }

    protected function getValidateIsUserLoggedInDirective(): Directive
    {
        if ($this->validateIsUserLoggedInDirective === null) {
            $this->validateIsUserLoggedInDirective = new Directive(
                $this->getValidateIsUserLoggedInForDirectivesFieldDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->validateIsUserLoggedInDirective;
    }

    /**
     * Provide the classes for all the directiveResolvers that need the "validateIsUserLoggedIn" directive
     *
     * @return FieldDirectiveResolverInterface[]
     */
    abstract protected function getFieldDirectiveResolvers(): array;
}
