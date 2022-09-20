<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInFieldDirectiveResolver;

class ValidateUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractUserStateConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserLoggedInFieldDirectiveResolver $validateIsUserLoggedInFieldDirectiveResolver = null;

    final public function setValidateIsUserLoggedInFieldDirectiveResolver(ValidateIsUserLoggedInFieldDirectiveResolver $validateIsUserLoggedInFieldDirectiveResolver): void
    {
        $this->validateIsUserLoggedInFieldDirectiveResolver = $validateIsUserLoggedInFieldDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInFieldDirectiveResolver(): ValidateIsUserLoggedInFieldDirectiveResolver
    {
        /** @var ValidateIsUserLoggedInFieldDirectiveResolver */
        return $this->validateIsUserLoggedInFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInFieldDirectiveResolver::class);
    }
}
