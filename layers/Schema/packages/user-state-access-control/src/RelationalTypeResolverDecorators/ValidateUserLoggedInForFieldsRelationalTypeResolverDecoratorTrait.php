<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait
{
    protected ?ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver = null;

    #[Required]
    public function autowireValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait(
        ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver,
    ): void {
        $this->validateIsUserLoggedInDirectiveResolver = $validateIsUserLoggedInDirectiveResolver;
    }

    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        return UserStates::IN == $entryValue;
    }
    protected function getValidateUserStateDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateIsUserLoggedInDirectiveResolver();
    }
}
