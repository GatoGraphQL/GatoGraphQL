<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInFieldDirectiveResolver;

class ValidateUserNotLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractUserStateConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserNotLoggedInFieldDirectiveResolver $validateIsUserNotLoggedInFieldDirectiveResolver = null;

    final public function setValidateIsUserNotLoggedInFieldDirectiveResolver(ValidateIsUserNotLoggedInFieldDirectiveResolver $validateIsUserNotLoggedInFieldDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInFieldDirectiveResolver = $validateIsUserNotLoggedInFieldDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInFieldDirectiveResolver(): ValidateIsUserNotLoggedInFieldDirectiveResolver
    {
        /** @var ValidateIsUserNotLoggedInFieldDirectiveResolver */
        return $this->validateIsUserNotLoggedInFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInFieldDirectiveResolver::class);
    }
}
