<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\Facades\RuntimeRegistries;

use PoP\Engine\App;
use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;

class UserAvatarRuntimeRegistryFacade
{
    public static function getInstance(): UserAvatarRuntimeRegistryInterface
    {
        /**
         * @var UserAvatarRuntimeRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(UserAvatarRuntimeRegistryInterface::class);
        return $service;
    }
}
