<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\Facades;

use PoPSchema\UserAvatars\TypeDataResolvers\UserAvatarTypeDataResolverInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserAvatarTypeDataResolverFacade
{
    public static function getInstance(): UserAvatarTypeDataResolverInterface
    {
        /**
         * @var UserAvatarTypeDataResolverInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserAvatarTypeDataResolverInterface::class);
        return $service;
    }
}
