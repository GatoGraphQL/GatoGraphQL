<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

trait ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait
{
    abstract protected function getValidateIsUserLoggedInDirectiveResolver(): ValidateIsUserLoggedInDirectiveResolver;

    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        return UserStates::IN == $entryValue;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserLoggedInDirectiveResolver();
    }
}
