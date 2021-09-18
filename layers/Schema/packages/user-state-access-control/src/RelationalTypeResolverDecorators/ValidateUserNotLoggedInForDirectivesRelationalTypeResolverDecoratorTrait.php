<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

trait ValidateUserNotLoggedInForDirectivesRelationalTypeResolverDecoratorTrait
{
    protected function getRequiredEntryValue(): ?string
    {
        return UserStates::OUT;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(ValidateIsUserNotLoggedInForDirectivesDirectiveResolver::class);
    }
}
