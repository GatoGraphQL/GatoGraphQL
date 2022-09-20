<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver;

class ValidateUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractUserStateConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateUserLoggedInForDirectivesRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver $validateIsUserLoggedInForDirectivesFieldDirectiveResolver = null;

    final public function setValidateIsUserLoggedInForDirectivesFieldDirectiveResolver(ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver $validateIsUserLoggedInForDirectivesFieldDirectiveResolver): void
    {
        $this->validateIsUserLoggedInForDirectivesFieldDirectiveResolver = $validateIsUserLoggedInForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInForDirectivesFieldDirectiveResolver(): ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver */
        return $this->validateIsUserLoggedInForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver::class);
    }
}
