<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators\ValidateUserLoggedInForDirectivesRelationalTypeResolverDecoratorTrait;

class ValidateUserLoggedInForDirectivesPrivateSchemaRelationalTypeResolverDecorator extends AbstractNoCacheConfigurableAccessControlForDirectivesInPrivateSchemaRelationalTypeResolverDecorator
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
