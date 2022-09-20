<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver;

trait ValidateUserLoggedInForDirectivesRelationalTypeResolverDecoratorTrait
{
    abstract protected function getValidateIsUserLoggedInForDirectivesFieldDirectiveResolver(): ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver;

    protected function getRequiredEntryValue(): ?string
    {
        return UserStates::IN;
    }
    protected function getValidateUserStateFieldDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserLoggedInForDirectivesFieldDirectiveResolver();
    }
}
