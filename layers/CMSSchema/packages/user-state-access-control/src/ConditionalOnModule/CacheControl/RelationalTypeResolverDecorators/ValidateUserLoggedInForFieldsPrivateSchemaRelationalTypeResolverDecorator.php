<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators\ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

class ValidateUserLoggedInForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractNoCacheConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
{
    use ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

    private ?ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver = null;

    final public function setValidateIsUserLoggedInDirectiveResolver(ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver): void
    {
        $this->validateIsUserLoggedInDirectiveResolver = $validateIsUserLoggedInDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInDirectiveResolver(): ValidateIsUserLoggedInDirectiveResolver
    {
        return $this->validateIsUserLoggedInDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
    }
}
