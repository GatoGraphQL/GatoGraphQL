<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;

trait ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait
{
    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        return UserStates::IN == $entryValue;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
    }
}
