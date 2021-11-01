<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

class ValidateUserNotLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractUserStateConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateUserNotLoggedInForDirectivesRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserNotLoggedInForDirectivesDirectiveResolver $validateIsUserNotLoggedInForDirectivesDirectiveResolver = null;

    final public function setValidateIsUserNotLoggedInForDirectivesDirectiveResolver(ValidateIsUserNotLoggedInForDirectivesDirectiveResolver $validateIsUserNotLoggedInForDirectivesDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInForDirectivesDirectiveResolver = $validateIsUserNotLoggedInForDirectivesDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInForDirectivesDirectiveResolver(): ValidateIsUserNotLoggedInForDirectivesDirectiveResolver
    {
        return $this->validateIsUserNotLoggedInForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInForDirectivesDirectiveResolver::class);
    }
}
