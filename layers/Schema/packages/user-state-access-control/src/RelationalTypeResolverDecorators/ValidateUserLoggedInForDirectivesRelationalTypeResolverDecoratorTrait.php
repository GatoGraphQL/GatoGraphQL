<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;

trait ValidateUserLoggedInForDirectivesRelationalTypeResolverDecoratorTrait
{
    protected function getRequiredEntryValue(): ?string
    {
        return UserStates::IN;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesDirectiveResolver::class);
    }
}
