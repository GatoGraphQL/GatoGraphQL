<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInFieldDirectiveResolver;

trait ValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait
{
    abstract protected function getValidateIsUserNotLoggedInFieldDirectiveResolver(): ValidateIsUserNotLoggedInFieldDirectiveResolver;

    protected function removeFieldNameBasedOnMatchingEntryValue(mixed $entryValue = null): bool
    {
        return UserStates::OUT === $entryValue;
    }
    protected function getValidateUserStateFieldDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserNotLoggedInFieldDirectiveResolver();
    }
}
