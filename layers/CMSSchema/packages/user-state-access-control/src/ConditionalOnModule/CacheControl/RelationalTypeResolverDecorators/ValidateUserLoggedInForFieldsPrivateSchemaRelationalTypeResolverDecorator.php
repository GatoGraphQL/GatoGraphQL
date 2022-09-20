<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators\ValidateUserLoggedInForFieldsRelationalTypeResolverDecoratorTrait;

class ValidateUserLoggedInForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractNoCacheConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
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
