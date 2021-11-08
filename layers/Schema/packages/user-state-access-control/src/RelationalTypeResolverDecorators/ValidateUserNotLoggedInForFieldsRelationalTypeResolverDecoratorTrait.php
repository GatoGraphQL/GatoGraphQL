<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;

trait ValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait
{
    abstract protected function getValidateIsUserNotLoggedInDirectiveResolver(): ValidateIsUserNotLoggedInDirectiveResolver;

    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        return UserStates::OUT == $entryValue;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserNotLoggedInDirectiveResolver();
    }
}
