<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;

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
