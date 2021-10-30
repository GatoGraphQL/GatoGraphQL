<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait
{
    // private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;

    /**
     * Service to be provided by the class, not the trait,
     * to avoid overriding a final method
     */
    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;
    // public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    // {
    //     $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    // }
    // protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    // {
    //     return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    // }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $validateUserStateDirectiveResolver = $this->getValidateUserStateDirectiveResolver();
        $validateUserStateDirectiveName = $validateUserStateDirectiveResolver->getDirectiveName();
        $validateUserStateDirective = $this->getFieldQueryInterpreter()->getDirective(
            $validateUserStateDirectiveName
        );
        return [
            $validateUserStateDirective,
        ];
    }

    abstract protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface;
}
