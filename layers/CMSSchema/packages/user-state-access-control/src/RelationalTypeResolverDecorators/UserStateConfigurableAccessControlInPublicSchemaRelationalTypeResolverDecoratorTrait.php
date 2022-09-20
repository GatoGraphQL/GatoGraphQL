<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

trait UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait
{
    protected ?Directive $validateUserStateDirective = null;

    /**
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        return [
            $this->getValidateUserStateDirective(),
        ];
    }

    protected function getValidateUserStateDirective(): Directive
    {
        if ($this->validateUserStateDirective === null) {
            $this->validateUserStateDirective = new Directive(
                $this->getValidateUserStateFieldDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->validateUserStateDirective;
    }

    abstract protected function getValidateUserStateFieldDirectiveResolver(): FieldDirectiveResolverInterface;
}
