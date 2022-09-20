<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver;

class ValidateUserNotLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractUserStateConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateUserNotLoggedInForDirectivesRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver $validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver = null;

    final public function setValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver(ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver $validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver = $validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver(): ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver */
        return $this->validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver::class);
    }
}
