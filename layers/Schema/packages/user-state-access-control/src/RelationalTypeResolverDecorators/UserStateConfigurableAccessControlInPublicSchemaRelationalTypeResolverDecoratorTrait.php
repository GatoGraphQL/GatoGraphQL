<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait
{
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;

    public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->getInstanceManager()->getInstance(FieldQueryInterpreterInterface::class);
    }

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
