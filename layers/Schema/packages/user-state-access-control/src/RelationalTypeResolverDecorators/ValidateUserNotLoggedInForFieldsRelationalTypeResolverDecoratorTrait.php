<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait ValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait
{
    private ?ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver = null;

    public function setValidateIsUserNotLoggedInDirectiveResolver(ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInDirectiveResolver = $validateIsUserNotLoggedInDirectiveResolver;
    }
    protected function getValidateIsUserNotLoggedInDirectiveResolver(): ValidateIsUserNotLoggedInDirectiveResolver
    {
        return $this->validateIsUserNotLoggedInDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInDirectiveResolver::class);
    }

    //#[Required]
    public function autowireValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait(
        ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver,
    ): void {
        $this->validateIsUserNotLoggedInDirectiveResolver = $validateIsUserNotLoggedInDirectiveResolver;
    }

    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        return UserStates::OUT == $entryValue;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserNotLoggedInDirectiveResolver();
    }
}
