<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators\ValidateUserNotLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

class ValidateUserNotLoggedInForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractNoCacheConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
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
