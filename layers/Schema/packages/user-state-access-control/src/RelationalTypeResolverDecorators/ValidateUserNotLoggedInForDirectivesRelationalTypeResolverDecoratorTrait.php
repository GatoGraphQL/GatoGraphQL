<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;
use Symfony\Contracts\Service\Attribute\Required;

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
