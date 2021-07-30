<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\Facades\RuntimeRegistries;

use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserAvatarRuntimeRegistryFacade
{
    public static function getInstance(): UserAvatarRuntimeRegistryInterface
    {
        /**
         * @var UserAvatarRuntimeRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserAvatarRuntimeRegistryInterface::class);
        return $service;
    }
}
