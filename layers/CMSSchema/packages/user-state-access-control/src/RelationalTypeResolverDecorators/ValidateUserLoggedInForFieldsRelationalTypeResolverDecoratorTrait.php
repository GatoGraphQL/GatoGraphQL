<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

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
