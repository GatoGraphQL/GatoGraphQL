<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnComponent\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

class NoCacheUserStateRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    private ?ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver = null;
    private ?ValidateIsUserLoggedInForDirectivesDirectiveResolver $validateIsUserLoggedInForDirectivesDirectiveResolver = null;
    private ?ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver = null;
    private ?ValidateIsUserNotLoggedInForDirectivesDirectiveResolver $validateIsUserNotLoggedInForDirectivesDirectiveResolver = null;

    final public function setValidateIsUserLoggedInDirectiveResolver(ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver): void
    {
        $this->validateIsUserLoggedInDirectiveResolver = $validateIsUserLoggedInDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInDirectiveResolver(): ValidateIsUserLoggedInDirectiveResolver
    {
        return $this->validateIsUserLoggedInDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
    }
    final public function setValidateIsUserLoggedInForDirectivesDirectiveResolver(ValidateIsUserLoggedInForDirectivesDirectiveResolver $validateIsUserLoggedInForDirectivesDirectiveResolver): void
    {
        $this->validateIsUserLoggedInForDirectivesDirectiveResolver = $validateIsUserLoggedInForDirectivesDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInForDirectivesDirectiveResolver(): ValidateIsUserLoggedInForDirectivesDirectiveResolver
    {
        return $this->validateIsUserLoggedInForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesDirectiveResolver::class);
    }
    final public function setValidateIsUserNotLoggedInDirectiveResolver(ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInDirectiveResolver = $validateIsUserNotLoggedInDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInDirectiveResolver(): ValidateIsUserNotLoggedInDirectiveResolver
    {
        return $this->validateIsUserNotLoggedInDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInDirectiveResolver::class);
    }
    final public function setValidateIsUserNotLoggedInForDirectivesDirectiveResolver(ValidateIsUserNotLoggedInForDirectivesDirectiveResolver $validateIsUserNotLoggedInForDirectivesDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInForDirectivesDirectiveResolver = $validateIsUserNotLoggedInForDirectivesDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInForDirectivesDirectiveResolver(): ValidateIsUserNotLoggedInForDirectivesDirectiveResolver
    {
        return $this->validateIsUserNotLoggedInForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInForDirectivesDirectiveResolver::class);
    }

    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    /**
     * If validating if the user is logged-in, then we can't cache the response
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $noCacheControlDirective = CacheControlHelper::getNoCacheDirective();
        return [
            $this->getValidateIsUserLoggedInDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->getValidateIsUserLoggedInForDirectivesDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->getValidateIsUserNotLoggedInDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->getValidateIsUserNotLoggedInForDirectivesDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
        ];
    }
}
