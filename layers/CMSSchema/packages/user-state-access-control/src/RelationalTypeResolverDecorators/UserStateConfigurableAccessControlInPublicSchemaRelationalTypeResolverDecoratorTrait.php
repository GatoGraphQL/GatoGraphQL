<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

trait UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait
{
    protected ?Directive $validateUserStateDirective = null;

    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;

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
                $this->getValidateUserStateDirectiveResolver()->getDirectiveName(),
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->validateUserStateDirective;
    }

    abstract protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface;
}
