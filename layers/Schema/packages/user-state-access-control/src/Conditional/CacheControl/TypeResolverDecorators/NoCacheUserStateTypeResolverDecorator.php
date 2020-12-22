<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\CacheControl\Helpers\CacheControlHelper;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;

class NoCacheUserStateTypeResolverDecorator extends AbstractTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
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
        return [
            ValidateIsUserLoggedInDirectiveResolver::getDirectiveName() => [
                $noCacheControlDirective,
            ],
            ValidateIsUserLoggedInForDirectivesDirectiveResolver::getDirectiveName() => [
                $noCacheControlDirective,
            ],
            ValidateIsUserNotLoggedInDirectiveResolver::getDirectiveName() => [
                $noCacheControlDirective,
            ],
            ValidateIsUserNotLoggedInForDirectivesDirectiveResolver::getDirectiveName() => [
                $noCacheControlDirective,
            ],
        ];
    }
}
