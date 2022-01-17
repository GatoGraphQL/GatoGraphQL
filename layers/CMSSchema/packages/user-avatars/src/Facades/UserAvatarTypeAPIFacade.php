<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatars\Facades;

use PoP\Root\App;
use PoPCMSSchema\UserAvatars\TypeAPIs\UserAvatarTypeAPIInterface;

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
