<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;

class ValidateUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractUserStateConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateUserLoggedInForDirectivesRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserLoggedInForDirectivesDirectiveResolver $validateIsUserLoggedInForDirectivesDirectiveResolver = null;

    final public function setValidateIsUserLoggedInForDirectivesDirectiveResolver(ValidateIsUserLoggedInForDirectivesDirectiveResolver $validateIsUserLoggedInForDirectivesDirectiveResolver): void
    {
        $this->validateIsUserLoggedInForDirectivesDirectiveResolver = $validateIsUserLoggedInForDirectivesDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInForDirectivesDirectiveResolver(): ValidateIsUserLoggedInForDirectivesDirectiveResolver
    {
        return $this->validateIsUserLoggedInForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesDirectiveResolver::class);
    }
}
