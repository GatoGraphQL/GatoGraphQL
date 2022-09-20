<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver;

class NoCacheUserStateRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    private ?ValidateIsUserLoggedInFieldDirectiveResolver $validateIsUserLoggedInFieldDirectiveResolver = null;
    private ?ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver $validateIsUserLoggedInForDirectivesFieldDirectiveResolver = null;
    private ?ValidateIsUserNotLoggedInFieldDirectiveResolver $validateIsUserNotLoggedInFieldDirectiveResolver = null;
    private ?ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver $validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver = null;

    final public function setValidateIsUserLoggedInFieldDirectiveResolver(ValidateIsUserLoggedInFieldDirectiveResolver $validateIsUserLoggedInFieldDirectiveResolver): void
    {
        $this->validateIsUserLoggedInFieldDirectiveResolver = $validateIsUserLoggedInFieldDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInFieldDirectiveResolver(): ValidateIsUserLoggedInFieldDirectiveResolver
    {
        /** @var ValidateIsUserLoggedInFieldDirectiveResolver */
        return $this->validateIsUserLoggedInFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInFieldDirectiveResolver::class);
    }
    final public function setValidateIsUserLoggedInForDirectivesFieldDirectiveResolver(ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver $validateIsUserLoggedInForDirectivesFieldDirectiveResolver): void
    {
        $this->validateIsUserLoggedInForDirectivesFieldDirectiveResolver = $validateIsUserLoggedInForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateIsUserLoggedInForDirectivesFieldDirectiveResolver(): ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver */
        return $this->validateIsUserLoggedInForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver::class);
    }
    final public function setValidateIsUserNotLoggedInFieldDirectiveResolver(ValidateIsUserNotLoggedInFieldDirectiveResolver $validateIsUserNotLoggedInFieldDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInFieldDirectiveResolver = $validateIsUserNotLoggedInFieldDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInFieldDirectiveResolver(): ValidateIsUserNotLoggedInFieldDirectiveResolver
    {
        /** @var ValidateIsUserNotLoggedInFieldDirectiveResolver */
        return $this->validateIsUserNotLoggedInFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInFieldDirectiveResolver::class);
    }
    final public function setValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver(ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver $validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver): void
    {
        $this->validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver = $validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver(): ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver */
        return $this->validateIsUserNotLoggedInForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver::class);
    }

    /**
     * @return array<class-string<RelationalTypeResolverInterface>|string> Either the class, or the constant "*" to represent _any_ class
     */
    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    /**
     * If validating if the user is logged-in, then we can't cache the response
     * @return array<string,Directive[]>
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $noCacheControlDirective = CacheControlHelper::getNoCacheDirective();
        return [
            $this->getValidateIsUserLoggedInFieldDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->getValidateIsUserLoggedInForDirectivesFieldDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->getValidateIsUserNotLoggedInFieldDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->getValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver()->getDirectiveName() => [
                $noCacheControlDirective,
            ],
        ];
    }
}
