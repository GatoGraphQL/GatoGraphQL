<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

class NoCacheUserStateTypeResolverDecorator extends AbstractTypeResolverDecorator
{
    public function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * If validating if the user is logged-in, then we can't cache the response
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $noCacheControlDirective = CacheControlHelper::getNoCacheDirective();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $validateIsUserLoggedInDirectiveResolver = $instanceManager->getInstance(ValidateIsUserLoggedInDirectiveResolver::class);
        /** @var DirectiveResolverInterface */
        $validateIsUserLoggedInForDirectivesDirectiveResolver = $instanceManager->getInstance(ValidateIsUserLoggedInForDirectivesDirectiveResolver::class);
        /** @var DirectiveResolverInterface */
        $validateIsUserNotLoggedInDirectiveResolver = $instanceManager->getInstance(ValidateIsUserNotLoggedInDirectiveResolver::class);
        /** @var DirectiveResolverInterface */
        $validateIsUserNotLoggedInForDirectivesDirectiveResolver = $instanceManager->getInstance(ValidateIsUserNotLoggedInForDirectivesDirectiveResolver::class);
        return [
            $validateIsUserLoggedInDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $validateIsUserLoggedInForDirectivesDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $validateIsUserNotLoggedInDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
            $validateIsUserNotLoggedInForDirectivesDirectiveResolver->getDirectiveName() => [
                $noCacheControlDirective,
            ],
        ];
    }
}
