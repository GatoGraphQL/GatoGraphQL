<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;

abstract class AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $validateIsUserLoggedInDirective = null;

    private ?ValidateIsUserLoggedInForDirectivesDirectiveResolver $validateIsUserLoggedInForDirectivesDirectiveResolver = null;

    final public function setValidateIsUserLoggedInForDirectivesDirectiveResolver(ValidateIsUserLoggedInForDirectivesDirectiveResolver $validateIsUserLoggedInForDirectivesDirectiveResolver): void
    {
        $this->validateIsUserLoggedInForDirectivesDirectiveResolver = $validateIsUserLoggedInForDirectivesDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInForDirectivesDirectiveResolver(): ValidateIsUserLoggedInForDirectivesDirectiveResolver
    {
        return $this->validateIsUserLoggedInForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesDirectiveResolver::class);
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
        foreach ($this->getDirectiveResolvers() as $needValidateIsUserLoggedInDirectiveResolver) {
            $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirectiveResolver->getDirectiveName()] = [
                $validateIsUserLoggedInDirective,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }

    protected function getValidateIsUserLoggedInDirective(): Directive
    {
        if ($this->validateIsUserLoggedInDirective === null) {
            $this->validateIsUserLoggedInDirective = new Directive(
                $this->getValidateIsUserLoggedInForDirectivesDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->validateIsUserLoggedInDirective;
    }

    /**
     * Provide the classes for all the directiveResolvers that need the "validateIsUserLoggedIn" directive
     *
     * @return DirectiveResolverInterface[]
     */
    abstract protected function getDirectiveResolvers(): array;
}
