<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\ConditionalOnComponent\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

class NoCacheUserStateRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver,
        protected ValidateIsUserLoggedInForDirectivesDirectiveResolver $validateIsUserLoggedInForDirectivesDirectiveResolver,
        protected ValidateIsUserNotLoggedInDirectiveResolver $validateIsUserNotLoggedInDirectiveResolver,
        protected ValidateIsUserNotLoggedInForDirectivesDirectiveResolver $validateIsUserNotLoggedInForDirectivesDirectiveResolver,
    ) {
        parent::__construct(
            $instanceManager,
            $fieldQueryInterpreter,
        );
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
            $this->validateIsUserLoggedInDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->validateIsUserLoggedInForDirectivesDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->validateIsUserNotLoggedInDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $this->validateIsUserNotLoggedInForDirectivesDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
        ];
    }
}
