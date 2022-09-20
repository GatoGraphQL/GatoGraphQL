<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInFieldDirectiveResolver;

trait ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait
{
    abstract protected function getValidateIsUserLoggedInFieldDirectiveResolver(): ValidateIsUserLoggedInFieldDirectiveResolver;

    protected function removeFieldNameBasedOnMatchingEntryValue(mixed $entryValue = null): bool
    {
        return UserStates::IN === $entryValue;
    }
    protected function getValidateUserStateFieldDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserLoggedInFieldDirectiveResolver();
    }
}
