<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\Facades;

use PoP\Engine\App;
use PoPSchema\UserAvatars\TypeAPIs\UserAvatarTypeAPIInterface;

class UserAvatarTypeAPIFacade
{
    public static function getInstance(): UserAvatarTypeAPIInterface
    {
        /**
         * @var UserAvatarTypeAPIInterface
         */
        $service = App::getContainer()->get(UserAvatarTypeAPIInterface::class);
        return $service;
    }
}
