<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\Facades\RuntimeRegistries;

use PoP\Root\App;
use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;

class UserAvatarRuntimeRegistryFacade
{
    public static function getInstance(): UserAvatarRuntimeRegistryInterface
    {
        /**
         * @var UserAvatarRuntimeRegistryInterface
         */
        $service = App::getContainer()->get(UserAvatarRuntimeRegistryInterface::class);
        return $service;
    }
}
