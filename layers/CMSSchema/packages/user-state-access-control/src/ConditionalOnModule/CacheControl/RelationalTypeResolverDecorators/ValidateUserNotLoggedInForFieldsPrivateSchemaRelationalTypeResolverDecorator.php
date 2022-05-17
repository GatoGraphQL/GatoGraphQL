<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators\ValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

class ValidateUserNotLoggedInForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractNoCacheConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
{
    use ValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver = null;

    final public function setValidateIsUserNotLoggedInDirectiveResolver(ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInDirectiveResolver = $validateIsUserNotLoggedInDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInDirectiveResolver(): ValidateIsUserNotLoggedInDirectiveResolver
    {
        return $this->validateIsUserNotLoggedInDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInDirectiveResolver::class);
    }
}
