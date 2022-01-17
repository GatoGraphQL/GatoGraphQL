<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

trait ValidateUserNotLoggedInForDirectivesRelationalTypeResolverDecoratorTrait
{
    abstract protected function getValidateIsUserNotLoggedInForDirectivesDirectiveResolver(): ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

    protected function getRequiredEntryValue(): ?string
    {
        return UserStates::OUT;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserNotLoggedInForDirectivesDirectiveResolver();
    }
}
